<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Medicine, Category, Pharmacy, Order, OrderItem, StockMovement, Invoice, Payment};
// ============================================================
// فایل: app/Http/Controllers/Admin/MedicineController.php
// توضیح: مدیریت کامل داروها (CRUD + تصویر + انبار)
// ============================================================
class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('category')->latest();

        // فیلترها
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                    ->orWhere('generic_name', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filter === 'low_stock') {
            $query->lowStock();
        }

        if ($request->filter === 'expiring') {
            $query->expiringSoon(30);
        }

        $medicines  = $query->paginate(20)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.medicines.index', compact('medicines', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.medicines.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'            => 'required|exists:categories,id',
            'name'                   => 'required|string|max:191',
            'generic_name'           => 'required|string|max:191',
            'barcode'                => 'nullable|string|unique:medicines,barcode',
            'unit'                   => 'required|string',
            'purchase_price'         => 'required|numeric|min:0',
            'sale_price'             => 'required|numeric|min:0',
            'stock'                  => 'required|integer|min:0',
            'min_stock'              => 'required|integer|min:0',
            'expiry_date'            => 'nullable|date|after:today',
            'manufacturer'           => 'nullable|string|max:191',
            'image'                  => 'nullable|image|max:2048',
            'requires_prescription'  => 'boolean',
        ]);

        // آپلود تصویر
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('medicines', 'public');
        }

        $medicine = Medicine::create($validated);

        // ثبت ورود اولیه موجودی در انبار
        if ($medicine->stock > 0) {
            StockMovement::create([
                'medicine_id'  => $medicine->id,
                'user_id'      => auth()->id(),
                'type'         => 'in',
                'quantity'     => $medicine->stock,
                'stock_before' => 0,
                'stock_after'  => $medicine->stock,
                'note'         => 'موجودی اولیه هنگام ثبت دارو',
            ]);
        }

        return redirect()->route('admin.medicines.index')
            ->with('success', 'دارو با موفقیت ثبت شد.');
    }

    public function edit(Medicine $medicine)
    {
        $categories = Category::active()->get();
        return view('admin.medicines.edit', compact('medicine', 'categories'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:191',
            'generic_name'  => 'required|string|max:191',
            'barcode'       => 'nullable|string|unique:medicines,barcode,' . $medicine->id,
            'unit'          => 'required|string',
            'purchase_price'=> 'required|numeric|min:0',
            'sale_price'    => 'required|numeric|min:0',
            'min_stock'     => 'required|integer|min:0',
            'expiry_date'   => 'nullable|date',
            'manufacturer'  => 'nullable|string|max:191',
            'image'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // حذف تصویر قدیمی
            if ($medicine->image) {
                \Storage::disk('public')->delete($medicine->image);
            }
            $validated['image'] = $request->file('image')->store('medicines', 'public');
        }

        $medicine->update($validated);

        return redirect()->route('admin.medicines.index')
            ->with('success', 'دارو بروزرسانی شد.');
    }

    public function destroy(Medicine $medicine)
    {
        // چک: آیا در سفارش فعال وجود دارد؟
        $hasActiveOrders = $medicine->orderItems()
            ->whereHas('order', fn($q) => $q->whereNotIn('status', ['delivered', 'cancelled']))
            ->exists();

        if ($hasActiveOrders) {
            return back()->with('error', 'این دارو در سفارش‌های جاری موجود است و قابل حذف نیست.');
        }

        $medicine->delete(); // SoftDelete
        return back()->with('success', 'دارو حذف شد.');
    }

    // تنظیم دستی موجودی
    public function adjustStock(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'type'     => 'required|in:in,out,adjustment,return',
            'note'     => 'required|string|max:255',
        ]);

        $stockBefore = $medicine->stock;

        if ($request->type === 'adjustment') {
            // تعدیل: مقدار = موجودی جدید مطلق
            $newStock = $request->quantity;
        } elseif (in_array($request->type, ['out'])) {
            if ($medicine->stock < $request->quantity) {
                return back()->with('error', 'موجودی کافی نیست.');
            }
            $newStock = $medicine->stock - $request->quantity;
        } else {
            $newStock = $medicine->stock + $request->quantity;
        }

        $medicine->update(['stock' => $newStock]);

        StockMovement::create([
            'medicine_id'  => $medicine->id,
            'user_id'      => auth()->id(),
            'type'         => $request->type,
            'quantity'     => $request->quantity,
            'stock_before' => $stockBefore,
            'stock_after'  => $newStock,
            'note'         => $request->note,
        ]);

        return back()->with('success', 'موجودی بروزرسانی شد.');
    }
}
