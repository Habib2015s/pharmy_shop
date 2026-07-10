{{-- ============================================================
     فایل: resources/views/admin/dashboard/index.blade.php
     توضیح: صفحه داشبورد با آمار، نمودار فروش و جدول اخیر
     ============================================================ --}}
@extends('admin.layout.app')

@section('title', 'داشبورد')

@section('breadcrumb')
    <li class="breadcrumb-item active">داشبورد</li>
@endsection

@section('content')

    {{-- ─── ردیف کارت‌های آمار ───────────────────────────────── --}}
    <div class="row g-3 mb-4">

        {{-- سفارشات امروز --}}
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="border-color:#1a6b3c">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="value">{{ number_format($stats['total_orders_today']) }}</div>
                        <div class="label">سفارش امروز</div>
                    </div>
                    <div class="icon" style="background:#e8f5ee;color:#1a6b3c">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- تحویل امروز --}}
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="border-color:#2c7be5">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="value">{{ number_format($stats['delivered_today']) }}</div>
                        <div class="label">تحویل امروز</div>
                    </div>
                    <div class="icon" style="background:#e8f0fe;color:#2c7be5">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- در انتظار --}}
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="border-color:#f0a500">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="value">{{ number_format($stats['pending_orders']) }}</div>
                        <div class="label">در انتظار بررسی</div>
                    </div>
                    <div class="icon" style="background:#fff8e1;color:#f0a500">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- درآمد ماهانه --}}
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="border-color:#e91e63">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="value" style="font-size:1.2rem">
                            {{ number_format($stats['monthly_revenue']) }}
                        </div>
                        <div class="label">درآمد ماه (تومان)</div>
                    </div>
                    <div class="icon" style="background:#fce4ec;color:#e91e63">
                        <i class="fas fa-toman-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── ردیف دوم: نمودارها ────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        {{-- نمودار فروش ۶ ماه --}}
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-chart-line text-primary me-2"></i>فروش ۶ ماه اخیر</span>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- دونات وضعیت سفارش‌ها --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-chart-donut text-success me-2"></i>وضعیت سفارش‌ها
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart" style="max-height:220px"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── ردیف سوم: جداول ──────────────────────────────────── --}}
    <div class="row g-3">

        {{-- آخرین سفارش‌ها --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list me-2"></i>آخرین سفارش‌ها</span>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">همه</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>شماره</th>
                                <th>داروخانه</th>
                                <th>مبلغ</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="text-decoration-none fw-600">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->pharmacy->name }}</td>
                                    <td>{{ number_format($order->final_amount) }} <small>ت</small></td>
                                    <td>
                                    <span class="badge bg-{{ $order->statusColor() }} status-badge">
                                        {{ $order->statusLabel() }}
                                    </span>
                                    </td>
                                    <td class="text-muted" style="font-size:.78rem">
                                        {{ verta($order->created_at)->format('Y/m/d') }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- داروهای کم‌موجودی --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    کمبود موجودی
                    <span class="badge bg-warning text-dark ms-1">{{ $stats['low_stock_count'] }}</span>
                </span>
                    <a href="{{ route('admin.reports.low-stock') }}" class="btn btn-sm btn-outline-warning">همه</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>نام دارو</th>
                                <th>موجودی</th>
                                <th>حداقل</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lowStockMedicines as $med)
                                <tr>
                                    <td>
                                        <div style="font-size:.85rem;font-weight:600">{{ $med->name }}</div>
                                        <small class="text-muted">{{ $med->category->name }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $med->stock }}</span>
                                    </td>
                                    <td class="text-muted">{{ $med->min_stock }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ─── داده از PHP → JS ────────────────────────────────────
        const salesLabels = @json($salesChart->keys());
        const salesData   = @json($salesChart->values());

        const statusLabels = @json($ordersByStatus->keys()->map(fn($s) => \App\Models\Order::STATUS_LABELS[$s] ?? $s));
        const statusData   = @json($ordersByStatus->values());

        // ─── نمودار خطی فروش ─────────────────────────────────────
        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'فروش (تومان)',
                    data: salesData,
                    borderColor: '#1a6b3c',
                    backgroundColor: 'rgba(26,107,60,.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#1a6b3c',
                    pointRadius: 5,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.parsed.y.toLocaleString('fa')} تومان`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => v.toLocaleString('fa')
                        }
                    }
                }
            }
        });

        // ─── دونات وضعیت سفارش‌ها ────────────────────────────────
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: ['#f0a500','#2c7be5','#6f42c1','#6c757d','#1a6b3c','#dc3545'],
                    borderWidth: 2
                }]
            },
            options: {
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Vazirmatn', size: 11 }, padding: 10 }
                    }
                }
            }
        });
    </script>
@endpush
