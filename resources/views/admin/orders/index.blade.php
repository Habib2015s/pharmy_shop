{{-- ============================================================
     فایل: resources/views/admin/orders/index.blade.php
     توضیح: لیست سفارش‌ها با فیلتر وضعیت و داروخانه
     ============================================================ --}}

@extends('admin.layout.app')

@section('title', 'سفارش‌ها')
@section('breadcrumb')
    <li class="breadcrumb-item active">سفارش‌ها</li>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">سفارش‌ها</h4>
            <small class="text-muted">مدیریت و پیگیری سفارشات داروخانه‌ها</small>
        </div>
        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> ثبت سفارش جدید
        </a>
    </div>

    {{-- خلاصه وضعیت‌ها --}}
    <div class="row g-2 mb-4">
        @foreach(\App\Models\Order::STATUS_LABELS as $key => $label)
            @php $count = \App\Models\Order::where('status',$key)->count(); @endphp
            <div class="col-6 col-md-2">
                <a href="{{ route('admin.orders.index', ['status'=>$key]) }}"
                   class="text-decoration-none">
                    <div class="card text-center p-2 {{ request('status')===$key ? 'border-primary' : '' }}">
                        <div class="fw-bold fs-5">{{ $count }}</div>
                        <small class="text-muted" style="font-size:.72rem">{{ $label }}</small>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- فیلترها --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-600">داروخانه</label>
                    <select name="pharmacy_id" class="form-select">
                        <option value="">همه داروخانه‌ها</option>
                        @foreach($pharmacies as $ph)
                            <option value="{{ $ph->id }}" {{ request('pharmacy_id') == $ph->id ? 'selected' : '' }}>
                                {{ $ph->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-600">وضعیت</label>
                    <select name="status" class="form-select">
                        <option value="">همه</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-600">از تاریخ</label>
                    <input type="date" name="date_from" class="form-control"
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-600">تا تاریخ</label>
                    <input type="date" name="date_to" class="form-control"
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary">اعمال فیلتر</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-shopping-cart me-2"></i>لیست سفارش‌ها
            <span class="badge bg-secondary ms-1">{{ $orders->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                    <tr>
                        <th>شماره سفارش</th>
                        <th>داروخانه</th>
                        <th>ثبت‌کننده</th>
                        <th>مبلغ نهایی</th>
                        <th>پرداخت</th>
                        <th>وضعیت</th>
                        <th>تاریخ ثبت</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-decoration-none fw-700 text-primary">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-600" style="font-size:.875rem">{{ $order->pharmacy->name }}</div>
                                <small class="text-muted">{{ $order->pharmacy->city }}</small>
                            </td>
                            <td class="text-muted" style="font-size:.82rem">{{ $order->user->name }}</td>
                            <td class="fw-700">
                                {{ number_format($order->final_amount) }}
                                <small class="text-muted fw-400">ت</small>
                            </td>
                            <td>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success">پرداخت شده</span>
                                @elseif($order->payment_status === 'partial')
                                    <span class="badge bg-warning text-dark">نیمه‌پرداخت</span>
                                @else
                                    <span class="badge bg-danger">پرداخت نشده</span>
                                @endif
                            </td>
                            <td>
                            <span class="badge bg-{{ $order->statusColor() }} status-badge">
                                {{ $order->statusLabel() }}
                            </span>
                            </td>
                            <td class="text-muted" style="font-size:.78rem">
                                {{ verta($order->created_at)->format('Y/m/d H:i') }}
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="btn btn-sm btn-outline-primary" title="جزئیات">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-shopping-cart fa-3x d-block mb-3 opacity-25"></i>
                                سفارشی یافت نشد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">
                {{ $orders->firstItem() }} تا {{ $orders->lastItem() }} از {{ $orders->total() }}
            </small>
            {{ $orders->links() }}
        </div>
    </div>

@endsection
