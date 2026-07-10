
{{-- ============================================================
     فایل: resources/views/shop/cart/index.blade.php
     توضیح: صفحه سبد خرید کامل
     ============================================================ --}}
@extends('layout.app')
@section('title', 'سبد خرید')

@section('content')
    <div class="container py-5">
        <h4 class="fw-800 mb-4">
            <i class="fas fa-shopping-cart text-success me-2"></i>سبد خرید
        </h4>

        @php $cart = session('cart', []) @endphp

        @if(empty($cart))
            <div class="text-center py-5">
                <div style="font-size:5rem">🛒</div>
                <h4 class="mt-3">سبد خرید شما خالی است</h4>
                <p class="text-muted">برای ادامه خرید به فروشگاه بروید</p>
                <a href="{{ route('shop.products') }}" class="btn btn-success btn-lg mt-2">
                    <i class="fas fa-pills me-2"></i> مشاهده داروها
                </a>
            </div>
        @else

            <div class="row g-4">
                {{-- لیست اقلام --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius:14px">
                        <div class="card-body p-0">
                            @foreach($cart as $id => $item)
                                <div class="d-flex align-items-center gap-3 p-4
                                {{ !$loop->last ? 'border-bottom' : '' }}">
                                    {{-- آیکون --}}
                                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width:70px;height:70px;background:#f0fdf4;color:#0ea372;font-size:1.8rem">
                                        💊
                                    </div>
                                    {{-- جزئیات --}}
                                    <div class="flex-grow-1">
                                        <h6 class="fw-700 mb-1">{{ $item['name'] }}</h6>
                                        <div class="text-muted" style="font-size:.82rem">{{ $item['generic_name'] ?? '' }}</div>
                                        <div class="text-success fw-700 mt-1">
                                            {{ number_format($item['price']) }} تومان / واحد
                                        </div>
                                    </div>
                                    {{-- تعداد --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="{{ route('shop.cart.update', $id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="decrease">
                                            <button class="qty-btn">−</button>
                                        </form>
                                        <span class="qty-display fw-700 fs-5">{{ $item['qty'] }}</span>
                                        <form action="{{ route('shop.cart.update', $id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="increase">
                                            <button class="qty-btn">+</button>
                                        </form>
                                    </div>
                                    {{-- جمع --}}
                                    <div class="text-end" style="min-width:100px">
                                        <div class="fw-800 text-success fs-5">
                                            {{ number_format($item['price'] * $item['qty']) }}
                                        </div>
                                        <small class="text-muted">تومان</small>
                                    </div>
                                    {{-- حذف --}}
                                    <form action="{{ route('shop.cart.remove', $id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-3">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- کد تخفیف --}}
                    <div class="card border-0 shadow-sm mt-3" style="border-radius:14px">
                        <div class="card-body p-3">
                            <h6 class="fw-700 mb-2">
                                <i class="fas fa-tag text-success me-2"></i>کد تخفیف
                            </h6>
                            <form action="{{ route('shop.cart.coupon') }}" method="POST"
                                  class="d-flex gap-2">
                                @csrf
                                <input type="text" name="coupon" class="form-control"
                                       placeholder="کد تخفیف را وارد کنید..."
                                       value="{{ session('coupon_code') }}">
                                <button class="btn btn-outline-success px-4">اعمال</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- خلاصه سفارش --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius:14px;position:sticky;top:80px">
                        <div class="card-header fw-700 border-0"
                             style="background:#f0fdf4;border-radius:14px 14px 0 0;color:#0b8560">
                            <i class="fas fa-receipt me-2"></i>خلاصه سفارش
                        </div>
                        <div class="card-body p-4">
                            @php
                                $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
                                $discount = session('cart_discount', 0);
                                $shipping = $subtotal >= 500000 ? 0 : 30000;
                                $total    = $subtotal - $discount + $shipping;
                            @endphp

                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span class="text-muted">جمع اقلام:</span>
                                <span class="fw-600">{{ number_format($subtotal) }} ت</span>
                            </div>

                            @if($discount > 0)
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <span class="text-danger">تخفیف:</span>
                                    <span class="text-danger fw-600">− {{ number_format($discount) }} ت</span>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span class="text-muted">هزینه ارسال:</span>
                                @if($shipping === 0)
                                    <span class="text-success fw-600">رایگان 🎉</span>
                                @else
                                    <span>{{ number_format($shipping) }} ت</span>
                                @endif
                            </div>

                            @if($shipping > 0)
                                <div class="alert alert-warning p-2 mb-3" style="font-size:.78rem;border-radius:8px">
                                    <i class="fas fa-info-circle me-1"></i>
                                    برای ارسال رایگان
                                    <strong>{{ number_format(500000 - $subtotal) }} تومان</strong>
                                    دیگر خرید کنید
                                </div>
                            @endif

                            <div class="d-flex justify-content-between mt-3">
                                <span class="fw-700 fs-5">مبلغ نهایی:</span>
                                <span class="fw-800 fs-4 text-success">
                            {{ number_format($total) }}
                            <small class="text-muted fw-400 fs-6">ت</small>
                        </span>
                            </div>

                            <div class="d-grid mt-4 gap-2">
                                <a href="{{ route('shop.checkout') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-lock me-2"></i> تکمیل خرید
                                </a>
                                <a href="{{ route('shop.products') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-right me-2"></i> ادامه خرید
                                </a>
                            </div>

                            {{-- trust --}}
                            <div class="d-flex gap-2 mt-3 justify-content-center flex-wrap">
                        <span style="font-size:.72rem;color:#64748b">
                            <i class="fas fa-shield-alt text-success me-1"></i>پرداخت امن
                        </span>
                                <span style="font-size:.72rem;color:#64748b">
                            <i class="fas fa-undo text-success me-1"></i>مرجوعی ۷ روزه
                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

