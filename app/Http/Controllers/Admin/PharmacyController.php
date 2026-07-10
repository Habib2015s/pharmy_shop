<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Medicine, Category, Pharmacy, Order, OrderItem, StockMovement, Invoice, Payment};

// ============================================================
// فایل: app/Http/Controllers/Admin/PharmacyController.php
// توضیح: مدیریت داروخانه‌ها
// ============================================================
class PharmacyController extends Controller
{
    public function index(Request $request)
    {
        $query = Pharmacy::withCount('orders')
            ->withSum(['orders' => fn($q) => $q->where('status', 'delivered')], 'final_amount')
            ->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                    ->orWhere('license_number', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%");
            });
        }

        $pharmacies = $query->paginate(15)->withQueryString();
        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    public function create()
    {
        return view('admin.pharmacies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:191',
            'owner_name'     => 'required|string|max:191',
            'license_number' => 'required|string|unique:pharmacies',
            'phone'          => 'required|string',
            'mobile'         => 'nullable|string',
            'email'          => 'nullable|email',
            'province'       => 'required|string',
            'city'           => 'required|string',
            'address'        => 'required|string',
            'credit_limit'   => 'required|numeric|min:0',
        ]);

        Pharmacy::create($request->validated());

        return redirect()->route('admin.pharmacies.index')
            ->with('success', 'داروخانه ثبت شد.');
    }

    public function show(Pharmacy $pharmacy)
    {
        $pharmacy->load(['orders' => fn($q) => $q->latest()->limit(10)]);
        $stats = [
            'total_orders'     => $pharmacy->orders()->count(),
            'delivered_orders' => $pharmacy->orders()->where('status', 'delivered')->count(),
            'total_paid'       => $pharmacy->payments()->sum('amount'),
            'balance'          => $pharmacy->current_balance,
        ];
        return view('admin.pharmacies.show', compact('pharmacy', 'stats'));
    }

    public function edit(Pharmacy $pharmacy)
    {
        return view('admin.pharmacies.edit', compact('pharmacy'));
    }

    public function update(Request $request, Pharmacy $pharmacy)
    {
        $request->validate([
            'name'           => 'required|string|max:191',
            'owner_name'     => 'required|string|max:191',
            'license_number' => 'required|string|unique:pharmacies,license_number,' . $pharmacy->id,
            'phone'          => 'required|string',
            'credit_limit'   => 'required|numeric|min:0',
        ]);

        $pharmacy->update($request->validated());
        return back()->with('success', 'اطلاعات داروخانه بروزرسانی شد.');
    }
}


