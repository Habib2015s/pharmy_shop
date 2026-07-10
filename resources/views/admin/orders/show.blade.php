{{-- ============================================================
     فایل: resources/views/admin/orders/show.blade.php
     توضیح: جزئیات کامل سفارش + تغییر وضعیت + timeline
     ============================================================ --}}
@extends('admin.layout.app')
@section('title', 'سفارش ' . $order->order_number)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">سفارش‌ها</a></li>
    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">سفارش {{ $order->order_number }}</h4>
            <small class="text-muted">ثبت شده در {{ verta($order->created_at)->format('Y/m/d H:i') }}</small>
        </div>
        <div class="d-flex gap-2">
            @if($order->invoice)
                <a href="{{ route('admin.invoices.pdf', $order->invoice) }}"
                   class="btn btn-outline-secondary" target="_blank">
                    <i class="fas fa-file-pdf me-1"></i> دانلود فاکتور
                </a>
            @endif
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-1"></i> بازگشت
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ─── جزئیات اصلی ────────────────────────────────── --}}
        <div class="col-lg-8">

            {{-- اقلام سفارش --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-list me-2"></i>اقلام سفارش
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>نام دارو</th>
                                <th>دسته</th>
                                <th>تعداد</th>
                                <th>قیمت واحد</th>
                                <th>تخفیف</th>
                                <th>جمع</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $i => $item)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="fw-600">{{ $item->medicine->name }}</div>
                                        <small class="text-muted">{{ $item->medicine->generic_name }}</small>
                                    </td>
                                    <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $item->medicine->category->name }}
                                    </span>
                                    </td>
                                    <td>
                                        <span class="fw-700">{{ number_format($item->quantity) }}</span>
                                        <small class="text-muted">{{ $item->medicine->unit }}</small>
                                    </td>
                                    <td>{{ number_format($item->unit_price) }} ت</td>
                                    <td>
                                        @if($item->discount > 0)
                                            <span class="text-danger">{{ number_format($item->discount) }} ت</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="fw-700 text-success">{{ number_format($item->subtotal) }} ت</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot style="background:#f8fafc">
                            <tr>
                                <td colspan="6" class="text-end fw-600">جمع کل:</td>
                                <td class="fw-700">{{ number_format($order->total_amount) }} ت</td>
                            </tr>
                            @if($order->discount > 0)
                                <tr>
                                    <td colspan="6" class="text-end text-danger">تخفیف:</td>
                                    <td class="text-danger fw-600">— {{ number_format($order->discount) }} ت</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="6" class="text-end text-muted">مالیات (۹٪):</td>
                                <td class="text-muted">{{ number_format($order->tax) }} ت</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end fw-700 fs-5">مبلغ نهایی:</td>
                                <td class="fw-700 fs-5 text-success">{{ number_format($order->final_amount) }} ت</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- تاریخچه حرکات انبار --}}
            @if($order->stockMovements->count())
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-history me-2"></i>حرکات انبار مرتبط
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle" style="font-size:.85rem">
                                <thead>
                                <tr>
                                    <th>دارو</th>
                                    <th>نوع</th>
                                    <th>تعداد</th>
                                    <th>قبل</th>
                                    <th>بعد</th>
                                    <th>توسط</th>
                                    <th>تاریخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->stockMovements as $mv)
                                    <tr>
                                        <td>{{ $mv->medicine->name }}</td>
                                        <td>
                                    <span class="badge {{ $mv->type === 'out' ? 'bg-danger' : 'bg-success' }}">
                                        {{ $mv->typeLabel() }}
                                    </span>
                                        </td>
                                        <td>{{ $mv->quantity }}</td>
                                        <td class="text-muted">{{ $mv->stock_before }}</td>
                                        <td class="text-muted">{{ $mv->stock_after }}</td>
                                        <td>{{ $mv->user->name }}</td>
                                        <td class="text-muted">{{ verta($mv->created_at)->format('Y/m/d H:i') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ─── ستون راست ──────────────────────────────────── --}}
        <div class="col-lg-4">

            {{-- وضعیت و تغییر --}}
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-tasks me-2"></i>وضعیت سفارش</div>
                <div class="card-body">
                    {{-- Timeline وضعیت --}}
                    <div class="timeline mb-4">
                        @foreach(\App\Models\Order::STATUS_LABELS as $key => $label)
                            @php
                                $steps = array_keys(\App\Models\Order::STATUS_LABELS);
                                $currentIdx = array_search($order->status, $steps);
                                $thisIdx    = array_search($key, $steps);
                                $isDone     = $thisIdx <= $currentIdx && $order->status !== 'cancelled';
                                $isCurrent  = $key === $order->status;
                            @endphp
                            @if($key !== 'cancelled')
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                         style="width:28px;height:28px;
                                    background:{{ $isDone ? '#1a6b3c' : '#e9ecef' }};
                                    color:{{ $isDone ? '#fff' : '#aaa' }};font-size:.7rem">
                                        @if($isDone) <i class="fas fa-check"></i> @else {{ $thisIdx + 1 }} @endif
                                    </div>
                                    <div>
                                        <div style="font-size:.85rem;font-weight:{{ $isCurrent ? '700' : '400' }};
                                        color:{{ $isCurrent ? '#1a6b3c' : ($isDone ? '#333' : '#aaa') }}">
                                            {{ $label }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- وضعیت فعلی --}}
                    <div class="text-center mb-3">
                    <span class="badge bg-{{ $order->statusColor() }} px-4 py-2" style="font-size:.9rem">
                        {{ $order->statusLabel() }}
                    </span>
                    </div>

                    {{-- دکمه‌های تغییر وضعیت --}}
                    @if(!in_array($order->status, ['delivered', 'cancelled']))
                        <div class="d-grid gap-2">
                            @php
                                $nextMap = [
                                    'pending'    => ['status' => 'confirmed',   'label' => 'تأیید سفارش',    'color' => 'info'],
                                    'confirmed'  => ['status' => 'processing',  'label' => 'شروع آماده‌سازی', 'color' => 'primary'],
                                    'processing' => ['status' => 'dispatched',  'label' => 'ارسال به راننده', 'color' => 'secondary'],
                                    'dispatched' => ['status' => 'delivered',   'label' => 'تأیید تحویل',     'color' => 'success'],
                                ];
                                $next = $nextMap[$order->status] ?? null;
                            @endphp

                            @if($next)
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $next['status'] }}">
                                    <button class="btn btn-{{ $next['color'] }} w-100">
                                        <i class="fas fa-arrow-left me-1"></i> {{ $next['label'] }}
                                    </button>
                                </form>
                            @endif

                            @if($order->canBeCancelled())
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST"
                                      onsubmit="return confirm('آیا از لغو این سفارش مطمئن هستید؟')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button class="btn btn-outline-danger w-100">
                                        <i class="fas fa-times me-1"></i> لغو سفارش
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- اطلاعات داروخانه --}}
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-hospital me-2"></i>داروخانه</div>
                <div class="card-body">
                    <div class="fw-700 mb-1">{{ $order->pharmacy->name }}</div>
                    <div class="text-muted" style="font-size:.85rem">
                        <i class="fas fa-user me-1"></i> {{ $order->pharmacy->owner_name }}
                    </div>
                    <div class="text-muted" style="font-size:.85rem">
                        <i class="fas fa-phone me-1"></i> {{ $order->pharmacy->phone }}
                    </div>
                    <div class="text-muted" style="font-size:.85rem">
                        <i class="fas fa-map-marker me-1"></i>
                        {{ $order->pharmacy->city }}، {{ $order->pharmacy->address }}
                    </div>
                </div>
            </div>

            {{-- اطلاعات ثبت --}}
            <div class="card">
                <div class="card-header"><i class="fas fa-info me-2"></i>اطلاعات ثبت</div>
                <div class="card-body" style="font-size:.85rem">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">ثبت‌کننده:</span>
                        <span>{{ $order->user->name }}</span>
                    </div>
                    @if($order->confirmed_at)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">تأیید:</span>
                            <span>{{ verta($order->confirmed_at)->format('Y/m/d H:i') }}</span>
                        </div>
                    @endif
                    @if($order->dispatched_at)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">ارسال:</span>
                            <span>{{ verta($order->dispatched_at)->format('Y/m/d H:i') }}</span>
                        </div>
                    @endif
                    @if($order->delivered_at)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">تحویل:</span>
                            <span>{{ verta($order->delivered_at)->format('Y/m/d H:i') }}</span>
                        </div>
                    @endif
                    @if($order->notes)
                        <hr>
                        <div class="text-muted">یادداشت:</div>
                        <p class="mb-0">{{ $order->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
