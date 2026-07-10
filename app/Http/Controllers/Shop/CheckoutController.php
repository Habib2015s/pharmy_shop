<?php

// ============================================================
// فایل: app/Http/Controllers/Shop/CheckoutController.php
// توضیح: صفحه پرداخت و ثبت سفارش فروشگاه
// ============================================================

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Medicine, Order, OrderItem, StockMovement};

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // باید وارد شده باشد
    }

    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.cart');
        }
        return view('shop.checkout.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string',
            'phone'          => 'required|string',
            'address'        => 'required|string',
            'payment_method' => 'required|in:cash,transfer,card',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.home');
        }

        DB::transaction(function () use ($request, $cart) {
            // سفارش ایجاد می‌شود با pharmacy_id=null (مشتری عادی)
            $order = Order::create([
                'order_number'   => Order::generateOrderNumber(),
                'pharmacy_id'    => auth()->user()->pharmacy?->id ?? 1, // fallback
                'user_id'        => auth()->id(),
                'status'         => 'pending',
                'payment_method' => $request->payment_method,
                'notes'          => "نام: {$request->name} | تلفن: {$request->phone} | آدرس: {$request->address}",
            ]);

            $total = 0;
            foreach ($cart as $id => $item) {
                $medicine = Medicine::findOrFail($id);
                $subtotal = $medicine->sale_price * $item['qty'];
                $total   += $subtotal;

                $order->items()->create([
                    'medicine_id' => $medicine->id,
                    'quantity'    => $item['qty'],
                    'unit_price'  => $medicine->sale_price,
                    'subtotal'    => $subtotal,
                ]);
            }

            $discount = session('cart_discount', 0);
            $order->update([
                'total_amount'  => $total,
                'discount'      => $discount,
                'tax'           => ($total - $discount) * 0.09,
                'final_amount'  => ($total - $discount) * 1.09,
            ]);

            session()->forget(['cart', 'coupon_code', 'cart_discount']);

            session(['last_order_id' => $order->id]);
        });

        return redirect()->route('shop.order.success');
    }
}

