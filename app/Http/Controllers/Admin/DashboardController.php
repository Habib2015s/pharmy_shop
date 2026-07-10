<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Medicine, Category, Pharmacy, Order, OrderItem, StockMovement, Invoice, Payment};

// ============================================================
// فایل: app/Http/Controllers/Admin/DashboardController.php
// توضیح: نمایش آمار کلی و نمودارها در صفحه اصلی پنل
// ============================================================
class DashboardController extends Controller
{
    public function index()
    {
        // آمار سریع کارت‌های بالای صفحه
        $stats = [
            'total_orders_today'     => Order::whereDate('created_at', today())->count(),
            'delivered_today'        => Order::whereDate('delivered_at', today())->where('status', 'delivered')->count(),
            'pending_orders'         => Order::where('status', 'pending')->count(),
            'low_stock_count'        => Medicine::lowStock()->active()->count(),
            'total_pharmacies'       => Pharmacy::active()->count(),
            'monthly_revenue'        => Order::thisMonth()->where('status', 'delivered')->sum('final_amount'),
            'expiring_medicines'     => Medicine::expiringSoon(30)->count(),
        ];

        // داده‌های نمودار فروش ۶ ماه اخیر (برای Chart.js)
        $salesChart = Order::selectRaw('MONTH(created_at) as month, SUM(final_amount) as total')
            ->where('status', 'delivered')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get()
            ->mapWithKeys(fn($row) => [
                jdate_month_name($row->month) => $row->total  // نام ماه شمسی
            ]);

        // ۵ سفارش اخیر
        $recentOrders = Order::with(['pharmacy', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        // داروهای کم‌موجودی
        $lowStockMedicines = Medicine::with('category')
            ->lowStock()
            ->active()
            ->limit(10)
            ->get();

        // توزیع سفارش بر اساس وضعیت (برای دونات چارت)
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.dashboard.index', compact(
            'stats', 'salesChart', 'recentOrders', 'lowStockMedicines', 'ordersByStatus'
        ));
    }
}

