<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Medicine, Category, Pharmacy, Order, OrderItem, StockMovement, Invoice, Payment};

// ============================================================
// فایل: app/Http/Controllers/Admin/OrderController.php
// توضیح: مدیریت سفارش‌ها — ثبت، تغییر وضعیت، تحویل
// ============================================================
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['pharmacy', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pharmacy_id')) {
            $query->where('pharmacy_id', $request->pharmacy_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders     = $query->paginate(15)->withQueryString();
        $pharmacies = Pharmacy::active()->get(['id', 'name']);
        $statuses   = Order::STATUS_LABELS;

        return view('admin.orders.index', compact('orders', 'pharmacies', 'statuses'));
    }

    public function create()
    {
        $pharmacies = Pharmacy::active()->get();

        $medicines = Medicine::active()
            ->get([
                'id',
                'name',
                'sale_price',
                'stock',
                'unit',
                'generic_name'
            ]);

        return view('admin.orders.create', compact(
            'pharmacies',
            'medicines'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pharmacy_id'              => 'required|exists:pharmacies,id',
            'items'                    => 'required|array|min:1',
            'items.*.medicine_id'      => 'required|exists:medicines,id',
            'items.*.quantity'         => 'required|integer|min:1',
            'items.*.discount'         => 'nullable|numeric|min:0',
            'discount'                 => 'nullable|numeric|min:0',
            'payment_method'           => 'nullable|in:cash,transfer,credit,cheque',
            'notes'                    => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $order = Order::create([
                'order_number'   => Order::generateOrderNumber(),
                'pharmacy_id'    => $validated['pharmacy_id'],
                'user_id'        => auth()->id(),
                'status'         => 'pending',
                'discount'       => $validated['discount'] ?? 0,
                'payment_method' => $validated['payment_method'] ?? null,
                'notes'          => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $medicine   = Medicine::findOrFail($item['medicine_id']);
                $rowDisc    = $item['discount'] ?? 0;
                $subtotal   = ($medicine->sale_price * $item['quantity']) - $rowDisc;

                $order->items()->create([
                    'medicine_id' => $medicine->id,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $medicine->sale_price,
                    'discount'    => $rowDisc,
                    'subtotal'    => $subtotal,
                ]);
            }

            // محاسبه مبالغ
            $order->recalculate();
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'سفارش با موفقیت ثبت شد.');
    }

    public function show(Order $order)
    {
        $order->load(['items.medicine.category', 'pharmacy', 'user', 'invoice', 'stockMovements.user']);
        return view('admin.orders.show', compact('order'));
    }

    // تغییر وضعیت سفارش
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:confirmed,processing,dispatched,delivered,cancelled',
        ]);

        $newStatus = $request->status;

        // هنگام تحویل: کاهش موجودی + صدور فاکتور
        if ($newStatus === 'delivered' && $order->status !== 'delivered') {

            // بررسی موجودی همه اقلام قبل از عملیات
            foreach ($order->items as $item) {
                if ($item->medicine->stock < $item->quantity) {
                    return back()->with('error',
                        "موجودی دارو «{$item->medicine->name}» کافی نیست. "
                        . "موجودی: {$item->medicine->stock} — درخواست: {$item->quantity}"
                    );
                }
            }

            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    $stockBefore = $item->medicine->stock;
                    $stockAfter  = $stockBefore - $item->quantity;

                    $item->medicine->decrement('stock', $item->quantity);

                    StockMovement::create([
                        'medicine_id'  => $item->medicine_id,
                        'user_id'      => auth()->id(),
                        'type'         => 'out',
                        'quantity'     => $item->quantity,
                        'stock_before' => $stockBefore,
                        'stock_after'  => $stockAfter,
                        'order_id'     => $order->id,
                        'note'         => 'تحویل به داروخانه ' . $order->pharmacy->name,
                    ]);
                }

                // صدور فاکتور
                if (!$order->invoice) {
                    Invoice::create([
                        'invoice_number' => Invoice::generateNumber(),
                        'order_id'       => $order->id,
                        'pharmacy_id'    => $order->pharmacy_id,
                        'amount'         => $order->final_amount,
                        'status'         => 'issued',
                        'due_date'       => now()->addDays(30),
                    ]);

                    // افزایش بدهی داروخانه
                    $order->pharmacy->increment('current_balance', $order->final_amount);
                }

                $order->update(['delivered_at' => now()]);
            });
        }

        // timestamp های مناسب
        $timestamps = [
            'confirmed'  => ['confirmed_at'  => now()],
            'dispatched' => ['dispatched_at' => now()],
        ];

        $order->update(array_merge(
            ['status' => $newStatus],
            $timestamps[$newStatus] ?? []
        ));

        return back()->with('success', 'وضعیت سفارش بروزرسانی شد.');
    }
}

