{{-- ============================================================
     فایل: resources/views/shop/checkout/index.blade.php
     توضیح: صفحه تکمیل سفارش — فرم آدرس + خلاصه
     ============================================================ --}}
@extends('layout.app')
@section('title', 'تکمیل خرید')

@section('content')
    <div class="container py-5">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-700"
                     style="width:32px;height:32px;background:#0ea372;font-size:.85rem">۱</div>
                <span class="fw-700 text-success" style="font-size:.9rem">سبد خرید</span>
            </div>
            <div style="flex:1;height:2px;background:#e2e8f0;position:relative">
                <div style="position:absolute;top:0;right:0;height:100%;width:100%;background:#0ea372"></div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-700"
                     style="width:32px;height:32px;background:#0ea372;font-size:.85rem">۲</div>
                <span class="fw-700 text-success" style="font-size:.9rem">تکمیل سفارش</span>
            </div>
            <div style="flex:1;height:2px;background:#e2e8f0"></div>
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-700"
                     style="width:32px;height:32px;background:#e2e8f0;color:#94a3b8;font-size:.85rem">۳</div>
                <span style="color:#94a3b8;font-size:.9rem">پرداخت</span>
            </div>
        </div>

        <form action="{{ route('shop.checkout.store') }}" method="POST">
            @csrf
            <div class="row g-4">

                {{-- ─── فرم اطلاعات ─────────────────────────── --}}
                <div class="col-lg-7">

                    {{-- اطلاعات گیرنده --}}
                    <div class="card border-0 shadow-sm mb-4" style="border-radius:14px">
                        <div class="card-header border-0 fw-700"
                             style="background:#f0fdf4;border-radius:14px 14px 0 0;color:#0b8560">
                            <i class="fas fa-user me-2"></i>اطلاعات گیرنده
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">نام و نام خانوادگی</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ auth()->user()->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-600">شماره موبایل</label>
                                    <input type="text" name="phone" class="form-control"
                                           placeholder="09xxxxxxxxx" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- آدرس تحویل --}}
                    <div class="card border-0 shadow-sm mb-4" style="border-radius:14px">
                        <div class="card-header border-0 fw-700"
                             style="background:#f0fdf4;border-radius:14px 14px 0 0;color:#0b8560">
                            <i class="fas fa-map-marker-alt me-2"></i>آدرس تحویل
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-600">استان</label>
                                    <select name="province" class="form-select" required>
                                        <option value="">انتخاب...</option>
                                        @foreach(['تهران','اصفهان','فارس','خراسان رضوی','آذربایجان شرقی','مازندران','گیلان','کرمان'] as $p)
                                            <option value="{{ $p }}">{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-600">شهر</label>
                                    <input type="text" name="city" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-600">کد پستی</label>
                                    <input type="text" name="postal_code" class="form-control"
                                           placeholder="۱۰ رقم">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-600">آدرس کامل</label>
                                    <textarea name="address" rows="3" class="form-control" required
                                              placeholder="خیابان، کوچه، پلاک، واحد..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- روش پرداخت --}}
                    <div class="card border-0 shadow-sm" style="border-radius:14px">
                        <div class="card-header border-0 fw-700"
                             style="background:#f0fdf4;border-radius:14px 14px 0 0;color:#0b8560">
                            <i class="fas fa-credit-card me-2"></i>روش پرداخت
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-2">
                                @foreach([
                                    ['card',     'fas fa-credit-card',  'پرداخت آنلاین',     'درگاه بانکی امن'],
                                    ['transfer', 'fas fa-university',   'انتقال بانکی',      'واریز به حساب'],
                                    ['cash',     'fas fa-money-bill',   'پرداخت در محل',     'هنگام تحویل'],
                                ] as $pm)
                                    <div class="col-md-4">
                                        <label class="d-block cursor-pointer">
                                            <input type="radio" name="payment_method"
                                                   value="{{ $pm[0] }}" class="d-none pay-radio"
                                                {{ $pm[0] === 'card' ? 'checked' : '' }}>
                                            <div class="p-3 rounded-3 text-center pay-option"
                                                 style="border:2px solid #e2e8f0;cursor:pointer;transition:all .2s">
                                                <i class="{{ $pm[1] }} fs-4 d-block mb-1 text-success"></i>
                                                <div class="fw-700" style="font-size:.85rem">{{ $pm[2] }}</div>
                                                <div class="text-muted" style="font-size:.72rem">{{ $pm[3] }}</div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── خلاصه سفارش ──────────────────────────── --}}
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm" style="border-radius:14px;position:sticky;top:80px">
                        <div class="card-header border-0 fw-700"
                             style="background:#f0fdf4;border-radius:14px 14px 0 0;color:#0b8560">
                            <i class="fas fa-receipt me-2"></i>خلاصه سفارش
                        </div>
                        <div class="card-body p-4">
                            @php
                                $cart     = session('cart', []);
                                $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
                                $discount = session('cart_discount', 0);
                                $shipping = $subtotal >= 500000 ? 0 : 30000;
                                $total    = $subtotal - $discount + $shipping;
                            @endphp

                            {{-- اقلام --}}
                            <div class="mb-3" style="max-height:220px;overflow-y:auto">
                                @foreach($cart as $item)
                                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                        <div>
                                            <div style="font-size:.85rem;font-weight:600">{{ $item['name'] }}</div>
                                            <small class="text-muted">× {{ $item['qty'] }}</small>
                                        </div>
                                        <span class="fw-600">
                                    {{ number_format($item['price'] * $item['qty']) }} ت
                                </span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- جمع --}}
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">جمع:</span>
                                <span>{{ number_format($subtotal) }} ت</span>
                            </div>
                            @if($discount)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-danger">تخفیف:</span>
                                    <span class="text-danger">− {{ number_format($discount) }} ت</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span class="text-muted">ارسال:</span>
                                <span class="{{ $shipping===0?'text-success fw-600':'' }}">
                                {{ $shipping===0 ? 'رایگان' : number_format($shipping).' ت' }}
                            </span>
                            </div>
                            <div class="d-flex justify-content-between fw-800">
                                <span>مبلغ نهایی:</span>
                                <span class="text-success fs-5">{{ number_format($total) }} ت</span>
                            </div>

                            <button type="submit" class="btn btn-success w-100 btn-lg mt-4 fw-700">
                                <i class="fas fa-lock me-2"></i> ثبت و پرداخت سفارش
                            </button>

                            <div class="text-center mt-3 d-flex justify-content-center gap-3"
                                 style="font-size:.72rem;color:#94a3b8">
                                <span><i class="fas fa-shield-alt me-1 text-success"></i>پرداخت امن</span>
                                <span><i class="fas fa-lock me-1 text-success"></i>SSL 256bit</span>
                                <span><i class="fas fa-undo me-1 text-success"></i>ضمانت بازگشت</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // highlight روش پرداخت انتخابی
        document.querySelectorAll('.pay-radio').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('.pay-option').forEach(el => {
                    el.style.borderColor = '#e2e8f0';
                    el.style.background  = '#fff';
                });
                if (radio.checked) {
                    const box = radio.nextElementSibling;
                    box.style.borderColor = '#0ea372';
                    box.style.background  = '#f0fdf4';
                }
            });
        });

        // init
        document.querySelector('.pay-radio:checked')?.dispatchEvent(new Event('change'));
    </script>
@endpush

