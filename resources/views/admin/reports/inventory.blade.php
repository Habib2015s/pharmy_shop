
{{-- ============================================================
     فایل: resources/views/admin/reports/inventory.blade.php
     توضیح: گزارش موجودی انبار + ارزش موجودی
     ============================================================ --}}
@extends('admin.layout.app')
@section('title', 'گزارش موجودی')
@section('breadcrumb')
    <li class="breadcrumb-item active">گزارش موجودی</li>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">گزارش موجودی انبار</h4>
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print me-1"></i> چاپ
        </button>
    </div>

    {{-- خلاصه --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="value">{{ number_format($summary['total_items']) }}</div>
                <div class="label">تعداد اقلام دارویی</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-color:#dc3545">
                <div class="value text-danger">{{ number_format($summary['low_stock']) }}</div>
                <div class="label">کمبود موجودی</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-color:#f0a500">
                <div class="value text-warning">{{ number_format($summary['expiring_soon']) }}</div>
                <div class="label">رو به انقضا (۳۰ روز)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-color:#2c7be5">
                <div class="value" style="font-size:1.1rem">{{ number_format($summary['total_stock_value']) }}</div>
                <div class="label">ارزش کل موجودی (ت)</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-boxes me-2"></i>جزئیات موجودی انبار
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                    <tr>
                        <th>نام دارو</th>
                        <th>دسته</th>
                        <th>موجودی</th>
                        <th>حداقل</th>
                        <th>وضعیت</th>
                        <th>قیمت خرید</th>
                        <th>ارزش موجودی</th>
                        <th>انقضا</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($medicines as $med)
                        <tr class="{{ $med->isLowStock() ? 'table-warning' : ($med->isExpired() ? 'table-danger' : '') }}">
                            <td>
                                <div class="fw-600">{{ $med->name }}</div>
                                <small class="text-muted">{{ $med->generic_name }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $med->category->name }}</span></td>
                            <td class="fw-700 {{ $med->isLowStock() ? 'text-danger' : 'text-success' }}">
                                {{ number_format($med->stock) }} {{ $med->unit }}
                            </td>
                            <td class="text-muted">{{ $med->min_stock }}</td>
                            <td>
                                @if($med->isExpired())
                                    <span class="badge bg-danger">منقضی</span>
                                @elseif($med->isLowStock())
                                    <span class="badge bg-warning text-dark">کم‌موجودی</span>
                                @else
                                    <span class="badge bg-success">مناسب</span>
                                @endif
                            </td>
                            <td>{{ number_format($med->purchase_price) }} ت</td>
                            <td class="fw-600 text-primary">
                                {{ number_format($med->stock * $med->purchase_price) }} ت
                            </td>
                            <td>
                                @if($med->expiry_date)
                                    <span class="{{ $med->isExpired() ? 'text-danger fw-bold' : 'text-muted' }}"
                                          style="font-size:.8rem">
                                    {{ verta($med->expiry_date)->format('Y/m/d') }}
                                </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
