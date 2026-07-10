

{{-- ============================================================
     فایل: resources/views/admin/reports/sales.blade.php
     توضیح: گزارش فروش با فیلتر تاریخ و نمودار
     ============================================================ --}}
@extends('admin.layout.app')
@section('title', 'گزارش فروش')
@section('breadcrumb')
    <li class="breadcrumb-item active">گزارش فروش</li>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">گزارش فروش</h4>
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print me-1"></i> چاپ
        </button>
    </div>

    {{-- فیلتر تاریخ --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-600">از تاریخ</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $from }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-600">تا تاریخ</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $to }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">اعمال</button>
                </div>
                {{-- میانبرهای سریع --}}
                <div class="col-md-4">
                    <div class="btn-group w-100">
                        <a href="?date_from={{ now()->startOfMonth()->toDateString() }}&date_to={{ now()->toDateString() }}"
                           class="btn btn-outline-secondary btn-sm">این ماه</a>
                        <a href="?date_from={{ now()->subMonth()->startOfMonth()->toDateString() }}&date_to={{ now()->subMonth()->endOfMonth()->toDateString() }}"
                           class="btn btn-outline-secondary btn-sm">ماه قبل</a>
                        <a href="?date_from={{ now()->startOfYear()->toDateString() }}&date_to={{ now()->toDateString() }}"
                           class="btn btn-outline-secondary btn-sm">امسال</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- کارت‌های خلاصه --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="value">{{ number_format($summary['total_orders']) }}</div>
                        <div class="label">تعداد سفارشات تحویلی</div>
                    </div>
                    <div class="icon" style="background:#e8f0fe;color:#2c7be5">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-color:#e91e63">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="value" style="font-size:1.3rem">
                            {{ number_format($summary['total_revenue']) }}
                        </div>
                        <div class="label">جمع درآمد (تومان)</div>
                    </div>
                    <div class="icon" style="background:#fce4ec;color:#e91e63">
                        <i class="fas fa-money-bill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-color:#f0a500">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="value" style="font-size:1.3rem">
                            {{ number_format(round($summary['avg_order'])) }}
                        </div>
                        <div class="label">میانگین هر سفارش (تومان)</div>
                    </div>
                    <div class="icon" style="background:#fff8e1;color:#f0a500">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- پرفروش‌ترین داروها --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-trophy text-warning me-2"></i>پرفروش‌ترین داروها
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>نام دارو</th>
                            <th>تعداد فروخته</th>
                            <th>جمع فروش</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($topMedicines as $i => $item)
                            <tr>
                                <td>
                                <span class="badge {{ $i===0?'bg-warning text-dark':($i===1?'bg-secondary':($i===2?'bg-danger':'bg-light text-dark border')) }}">
                                    {{ $i + 1 }}
                                </span>
                                </td>
                                <td class="fw-600">{{ $item->medicine->name }}</td>
                                <td>{{ number_format($item->total_qty) }}</td>
                                <td class="text-success fw-600">{{ number_format($item->total_amount) }} ت</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- بیشترین خرید به داروخانه --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-hospital text-primary me-2"></i>برترین داروخانه‌ها
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>داروخانه</th>
                            <th>سفارشات</th>
                            <th>جمع خرید</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($byPharmacy->take(10) as $i => $data)
                            <tr>
                                <td><span class="text-muted">{{ $i + 1 }}</span></td>
                                <td class="fw-600">{{ $data['name'] }}</td>
                                <td>{{ $data['count'] }}</td>
                                <td class="text-success fw-600">{{ number_format($data['total']) }} ت</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

