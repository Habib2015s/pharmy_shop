<?php

// ============================================================
// فایل: app/Http/Controllers/Shop/CartController.php
// توضیح: مدیریت سبد خرید با session
// ============================================================

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;

class CartController extends Controller
{
    // نمایش سبد
    public function index()
    {
        return view('shop.cart.index');
    }

    // افزودن به سبد
    public function add(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);

        if ($medicine->stock < $request->quantity) {
            return back()->with('error', 'موجودی کافی نیست.');
        }

        $cart = session('cart', []);
        $id   = $medicine->id;

        if (isset($cart[$id])) {
            // اگر قبلاً در سبد بود، تعداد را اضافه کن
            $newQty = $cart[$id]['qty'] + $request->quantity;
            if ($newQty > $medicine->stock) {
                return back()->with('error', 'موجودی کافی نیست.');
            }
            $cart[$id]['qty'] = $newQty;
        } else {
            $cart[$id] = [
                'medicine_id'  => $medicine->id,
                'name'         => $medicine->name,
                'generic_name' => $medicine->generic_name,
                'price'        => $medicine->sale_price,
                'unit'         => $medicine->unit,
                'qty'          => $request->quantity,
                'image'        => $medicine->image,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('cart_success', "«{$medicine->name}» به سبد خرید اضافه شد.");
    }

    // تغییر تعداد
    public function update(Request $request, $id)
    {
        $cart = session('cart', []);

        if (!isset($cart[$id])) {
            return back();
        }

        if ($request->action === 'increase') {
            $medicine = Medicine::find($id);
            if ($medicine && $cart[$id]['qty'] < $medicine->stock) {
                $cart[$id]['qty']++;
            }
        } elseif ($request->action === 'decrease') {
            $cart[$id]['qty']--;
            if ($cart[$id]['qty'] <= 0) {
                unset($cart[$id]);
            }
        }

        session(['cart' => $cart]);
        return back();
    }

    // حذف از سبد
    public function remove($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return back()->with('cart_success', 'محصول از سبد حذف شد.');
    }

    // اعمال کد تخفیف
    public function applyCoupon(Request $request)
    {
        $coupons = [
            'PHARMA10' => 0.10,   // ۱۰ درصد تخفیف
            'WELCOME20' => 0.20,  // ۲۰ درصد تخفیف
        ];

        $code = strtoupper($request->coupon);

        if (isset($coupons[$code])) {
            $cart     = session('cart', []);
            $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
            $discount = $subtotal * $coupons[$code];

            session([
                'coupon_code'     => $code,
                'cart_discount'   => $discount,
            ]);

            return back()->with('cart_success', "کد تخفیف اعمال شد: {$discount} تومان تخفیف گرفتید!");
        }

        return back()->with('error', 'کد تخفیف نامعتبر است.');
    }
}

