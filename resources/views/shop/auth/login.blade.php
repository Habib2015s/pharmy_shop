
{{-- ============================================================
     فایل: resources/views/shop/auth/login.blade.php
     توضیح: صفحه ورود فروشگاهی — دو بخش: خریدار و ادمین
     ============================================================ --}}
@extends('layout.app')
@section('title', 'ورود')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-7">

                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                         style="width:64px;height:64px;background:var(--green-light)">
                        <i class="fas fa-pills text-success fs-3"></i>
                    </div>
                    <h3 class="fw-800">ورود به فارمی‌شاپ</h3>
                    <p class="text-muted">برای ادامه خرید وارد شوید</p>
                </div>

                <div class="card border-0 shadow" style="border-radius:20px;overflow:hidden">

                    {{-- تب‌ها --}}
                    <div class="d-flex border-bottom">
                        <button class="flex-grow-1 py-3 fw-700 border-0 bg-success text-white tab-btn active"
                                id="tabLogin" onclick="switchTab('login')">
                            <i class="fas fa-sign-in-alt me-2"></i>ورود
                        </button>
                        <button class="flex-grow-1 py-3 fw-700 border-0 bg-white tab-btn"
                                id="tabRegister" onclick="switchTab('register')"
                                style="color:#64748b">
                            <i class="fas fa-user-plus me-2"></i>ثبت‌نام
                        </button>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        {{-- فرم ورود --}}
                        <div id="loginForm">
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">

                                <div class="mb-3">
                                    <label class="form-label fw-700">ایمیل</label>
                                    <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                        <input type="email" name="email"
                                               class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                               placeholder="email@example.com"
                                               value="{{ old('email') }}" required>
                                    </div>
                                    @error('email')
                                    <div class="text-danger mt-1" style="font-size:.82rem">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label fw-700">رمز عبور</label>
                                        <a href="{{ route('password.request') }}"
                                           class="text-success" style="font-size:.82rem">
                                            فراموشی رمز؟
                                        </a>
                                    </div>
                                    <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                        <input type="password" name="password"
                                               class="form-control border-start-0 ps-0"
                                               placeholder="رمز عبور خود را وارد کنید"
                                               id="passInput" required>
                                        <button type="button" class="input-group-text bg-light"
                                                onclick="togglePass()">
                                            <i class="fas fa-eye text-muted" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe" style="font-size:.85rem">
                                            مرا به خاطر بسپار
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success w-100 btn-lg fw-700">
                                    <i class="fas fa-sign-in-alt me-2"></i> ورود
                                </button>
                            </form>

                            {{-- لینک ادمین --}}
                            <div class="text-center mt-4 p-3 rounded-3" style="background:#f8fafc;font-size:.82rem">
                                <i class="fas fa-shield-alt text-success me-1"></i>
                                ادمین هستید؟
                                <a href="{{ route('admin.dashboard') }}" class="text-success fw-700">
                                    ورود به پنل مدیریت
                                </a>
                            </div>
                        </div>

                        {{-- فرم ثبت‌نام --}}
                        <div id="registerForm" style="display:none">
                            <form action="{{ route('register') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-700">نام کامل</label>
                                    <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                        <input type="text" name="name"
                                               class="form-control border-start-0 ps-0"
                                               placeholder="نام و نام خانوادگی" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-700">ایمیل</label>
                                    <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                        <input type="email" name="email"
                                               class="form-control border-start-0 ps-0"
                                               placeholder="email@example.com" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-700">شماره موبایل</label>
                                    <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-mobile text-muted"></i>
                                    </span>
                                        <input type="text" name="mobile"
                                               class="form-control border-start-0 ps-0"
                                               placeholder="09xxxxxxxxx">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-700">رمز عبور</label>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="حداقل ۸ کاراکتر" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-700">تکرار رمز عبور</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                           placeholder="رمز عبور را تکرار کنید" required>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                    <label class="form-check-label" for="terms" style="font-size:.83rem">
                                        <a href="#" class="text-success">شرایط و قوانین</a> را می‌پذیرم
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-success w-100 btn-lg fw-700">
                                    <i class="fas fa-user-plus me-2"></i> ثبت‌نام
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(tab) {
            const isLogin = tab === 'login';
            document.getElementById('loginForm').style.display    = isLogin ? '' : 'none';
            document.getElementById('registerForm').style.display = isLogin ? 'none' : '';
            document.getElementById('tabLogin').classList.toggle('bg-success', isLogin);
            document.getElementById('tabLogin').classList.toggle('text-white', isLogin);
            document.getElementById('tabLogin').classList.toggle('bg-white', !isLogin);
            document.getElementById('tabRegister').classList.toggle('bg-success', !isLogin);
            document.getElementById('tabRegister').classList.toggle('text-white', !isLogin);
            document.getElementById('tabRegister').classList.toggle('bg-white', isLogin);
        }

        function togglePass() {
            const inp = document.getElementById('passInput');
            const ico = document.getElementById('eyeIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                ico.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                inp.type = 'password';
                ico.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // اگر error ثبت‌نام بود، برو به تب ثبت‌نام
        @if($errors->has('name') || $errors->has('mobile'))
        switchTab('register');
        @endif
    </script>
@endpush
