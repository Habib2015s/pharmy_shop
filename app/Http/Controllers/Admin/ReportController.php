<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Medicine, Category, Pharmacy, Order, OrderItem, StockMovement, Invoice, Payment};

// ============================================================
// فایل: app/Http/Controllers/Admin/ReportController.php
// توضیح: گزارش‌های فروش، موجودی، داروخانه‌ها
// ============================================================
class ReportController extends Controller
{
    // گزارش فروش
    public function sales(Request $request)
    {
        $from = $request->date_from ?? now()->startOfMonth()->toDateString();
        $to   = $request->date_to   ?? now()->toDateString();

        $orders = Order::with(['pharmacy'])
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->get();

        $summary = [
            'total_orders'  => $orders->count(),
            'total_revenue' => $orders->sum('final_amount'),
            'avg_order'     => $orders->avg('final_amount'),
        ];

        // فروش بر اساس داروخانه
        $byPharmacy = $orders->groupBy('pharmacy_id')
            ->map(fn($group) => [
                'name'    => $group->first()->pharmacy->name,
                'count'   => $group->count(),
                'total'   => $group->sum('final_amount'),
            ])
            ->sortByDesc('total');

        // پرفروش‌ترین داروها
        $topMedicines = OrderItem::selectRaw('medicine_id, SUM(quantity) as total_qty, SUM(subtotal) as total_amount')
            ->whereHas('order', fn($q) => $q->where('status', 'delivered')
                ->whereBetween('delivered_at', [$from, $to]))
            ->with('medicine:id,name')
            ->groupBy('medicine_id')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        return view('admin.reports.sales', compact('orders', 'summary', 'byPharmacy', 'topMedicines', 'from', 'to'));
    }

    // گزارش موجودی انبار
    public function inventory()
    {
        $medicines = Medicine::with('category')
            ->active()
            ->orderBy('stock')
            ->get();

        $summary = [
            'total_items'       => $medicines->count(),
            'low_stock'         => $medicines->filter->isLowStock()->count(),
            'expiring_soon'     => Medicine::expiringSoon(30)->count(),
            'total_stock_value' => $medicines->sum(fn($m) => $m->stock * $m->purchase_price),
        ];

        return view('admin.reports.inventory', compact('medicines', 'summary'));
    }

    // داروهای کم‌موجودی
    public function lowStock()
    {
        $medicines = Medicine::with('category')
            ->lowStock()
            ->active()
            ->orderBy('stock')
            ->get();

        return view('admin.reports.low_stock', compact('medicines'));
    }
}
