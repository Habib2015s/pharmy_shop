{{-- ============================================================
     فایل: resources/views/admin/pharmacies/index.blade.php
     توضیح: لیست داروخانه‌ها با آمار سفارش و مانده حساب
     ============================================================ --}}
@extends('admin.layout.app')
@section('title', 'داروخانه‌ها')
@section('breadcrumb')
    <li class="breadcrumb-item active">داروخانه‌ها</li>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">داروخانه‌ها</h4>
            <small class="text-muted">مدیریت مشتریان</small>
        </div>
        <a href="{{ route('admin.pharmacies.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> ثبت داروخانه جدید
        </a>
    </div>

    {{-- جستجو --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-600">جستجو</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="نام داروخانه، شماره پروانه، شهر..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">جستجو</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.pharmacies.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-1"></i> پاک کردن
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-hospital me-2"></i>لیست داروخانه‌ها
            <span class="badge bg-secondary ms-1">{{ $pharmacies->total() }}</span>
        </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                    <tr>
                        <th>نام داروخانه</th>
                        <th>مالک</th>
                        <th>شهر</th>
                        <th>تلفن</th>
                        <th>تعداد سفارش</th>
                        <th>جمع خرید</th>
                        <th>مانده بدهی</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($pharmacies as $ph)
                        <tr>
                            <td>
                                <div class="fw-700">{{ $ph->name }}</div>
                                <small class="text-muted">پروانه: {{ $ph->license_number }}</small>
                            </td>
                            <td>{{ $ph->owner_name }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $ph->city }}</span>
                            </td>
                            <td>{{ $ph->phone }}</td>
                            <td class="text-center fw-600">{{ number_format($ph->orders_count) }}</td>
                            <td>
                                @php $total = $ph->orders_sum_final_amount ?? 0 @endphp
                                <span class="text-success fw-600">{{ number_format($total) }}</span>
                                <small class="text-muted">ت</small>
                            </td>
                            <td>
                            <span class="{{ $ph->current_balance > 0 ? 'text-danger fw-700' : 'text-muted' }}">
                                {{ number_format($ph->current_balance) }} ت
                            </span>
                            </td>
                            <td>
                                @if($ph->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-secondary">غیرفعال</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.pharmacies.show', $ph) }}"
                                       class="btn btn-sm btn-outline-primary" title="مشاهده">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pharmacies.edit', $ph) }}"
                                       class="btn btn-sm btn-outline-secondary" title="ویرایش">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{-- ثبت سفارش سریع --}}
                                    <a href="{{ route('admin.orders.create', ['pharmacy_id' => $ph->id]) }}"
                                       class="btn btn-sm btn-outline-success" title="سفارش جدید">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-hospital fa-3x d-block mb-3 opacity-25"></i>
                                داروخانه‌ای یافت نشد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ $pharmacies->total() }} داروخانه</small>
            {{ $pharmacies->links() }}
        </div>
    </div>

@endsection
