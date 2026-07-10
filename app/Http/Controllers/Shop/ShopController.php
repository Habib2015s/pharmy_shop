<?php

// ============================================================
// فایل: app/Http/Controllers/Shop/ShopController.php
// توضیح: کنترلر فروشگاه — صفحه اصلی، محصولات، جستجو
// ============================================================

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Medicine, Category};

class ShopController extends Controller
{
    // ─── صفحه اصلی ────────────────────────────────────────
    public function home()
    {
        $categories = Category::withCount(['medicines' => fn($q) => $q->where('is_active', true)])
            ->where('is_active', true)
            ->get();

        // پرفروش‌ترین (بر اساس تعداد در order_items)
        $featuredMedicines = Medicine::active()
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->with('category')
            ->limit(8)
            ->get();

        // جدیدترین‌ها
        $newMedicines = Medicine::active()
            ->with('category')
            ->latest()
            ->limit(4)
            ->get();

        return view('shop.home.index', compact(
            'categories', 'featuredMedicines', 'newMedicines'
        ));
    }

    // ─── لیست داروها با فیلتر ─────────────────────────────
    public function products(Request $request)
    {
        $query = Medicine::active()->with('category');

        // فیلتر دسته‌بندی
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // جستجو
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                    ->orWhere('generic_name', 'like', "%{$q}%");
            });
        }

        // فیلتر قیمت
        if ($request->filled('price_min')) {
            $query->where('sale_price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('sale_price', '<=', $request->price_max);
        }

        // فقط موجود
        if ($request->in_stock) {
            $query->where('stock', '>', 0);
        }

        // مرتب‌سازی
        match($request->sort) {
            'price_asc'  => $query->orderBy('sale_price'),
            'price_desc' => $query->orderByDesc('sale_price'),
            'newest'     => $query->latest(),
            default      => $query->orderByDesc('id'),
        };

        $medicines  = $query->paginate(16)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('shop.products.index', compact('medicines', 'categories'));
    }

    // ─── جستجو ───────────────────────────────────────────
    public function search(Request $request)
    {
        return redirect()->route('shop.products', ['q' => $request->q]);
    }

    // ─── جزئیات دارو ─────────────────────────────────────
    public function show(Medicine $medicine)
    {
        abort_if(!$medicine->is_active, 404);

        $related = Medicine::active()
            ->where('category_id', $medicine->category_id)
            ->where('id', '!=', $medicine->id)
            ->with('category')
            ->limit(4)
            ->get();

        return view('shop.products.show', compact('medicine', 'related'));
    }
}
