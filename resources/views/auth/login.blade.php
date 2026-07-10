{{-- resources/views/shop/auth/login.blade.php --}}
@extends('layout.app')
@section('title', 'ورود به فارمی‌شاپ')

@push('styles')
    <style>
        /* ═══ پس‌زمینه متحرک ═══ */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 2rem 0;
            background: linear-gradient(135deg, #064e3b 0%, #065f46 35%, #0b8560 70%, #0ea372 100%);
        }

        /* حلقه‌های شناور */
        .bg-ring {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,.1);
            pointer-events: none;
            animation: ring-expand 8s ease-in-out infinite;
        }

        @keyframes ring-expand {
            0%, 100% { transform: scale(1); opacity: .1; }
            50%       { transform: scale(1.06); opacity: .2; }
        }

        /* ذرات شناور */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
            pointer-events: none;
            animation: float-up linear infinite;
        }

        @keyframes float-up {
            0%   { transform: translateY(0) rotate(0deg); opacity: .08; }
            50%  { opacity: .18; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }

        /* ايكون‌های داروی شناور */
        .float-icon {
            position: absolute;
            font-size: 1.6rem;
            opacity: .13;
            pointer-events: none;
            animation: drift 12s ease-in-out infinite;
            filter: blur(.5px);
        }

        @keyframes drift {
            0%, 100% { transform: translate(0,0) rotate(-8deg); }
            33%       { transform: translate(15px,-20px) rotate(8deg); }
            66%       { transform: translate(-10px, 12px) rotate(-4deg); }
        }

        /* ═══ کارت ورود ═══ */
        .login-card {
            background: rgba(255,255,255,.97);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,.25), 0 0 0 1px rgba(255,255,255,.15);
            animation: card-enter .55s cubic-bezier(.22,.68,0,1.2) both;
        }

        @keyframes card-enter {
            from { opacity: 0; transform: translateY(30px) scale(.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* سربرگ کارت */
        .card-header-custom {
            background: linear-gradient(135deg, #0b8560, #0ea372);
            padding: 2rem 2rem 3.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header-custom::before {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            background: rgba(255,255,255,.07);
            border-radius: 50%;
            bottom: -80px; right: -40px;
        }

        .card-header-custom::after {
            content: '';
            position: absolute;
            width: 130px; height: 130px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
            top: -30px; left: -20px;
        }

        .header-logo {
            width: 70px; height: 70px;
            background: rgba(255,255,255,.18);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
            position: relative; z-index: 2;
            animation: logo-bounce .6s cubic-bezier(.22,.68,0,1.4) both .3s;
            border: 2px solid rgba(255,255,255,.25);
        }

        @keyframes logo-bounce {
            from { transform: scale(0) rotate(-20deg); }
            to   { transform: scale(1) rotate(0deg); }
        }

        .card-header-custom h4 {
            color: #fff;
            font-weight: 800;
            font-size: 1.3rem;
            margin-bottom: .3rem;
            position: relative; z-index: 2;
        }

        .card-header-custom p {
            color: rgba(255,255,255,.75);
            font-size: .82rem;
            margin: 0;
            position: relative; z-index: 2;
        }

        /* تب‌ها */
        .tabs-wrap {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            position: relative;
        }

        /* برجستگی پایین سربرگ کارت */
        .card-wave {
            height: 28px;
            background: #fff;
            margin-top: -24px;
            border-radius: 24px 24px 0 0;
            position: relative; z-index: 1;
        }

        .tab-btn {
            flex: 1;
            padding: .85rem;
            border: none;
            font-size: .88rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
            background: transparent;
            color: #94a3b8;
            position: relative;
        }

        .tab-btn.active {
            color: #0b8560;
            background: #fff;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 10%; right: 10%;
            height: 2.5px;
            background: #0b8560;
            border-radius: 2px;
        }

        .tab-btn i { margin-left: .4rem; }

        /* فرم‌ها */
        .form-wrap { padding: 1.75rem 2rem 2rem; }

        .input-group-custom {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-group-custom label {
            display: block;
            font-size: .8rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: .35rem;
        }

        .input-field {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: .72rem 2.8rem .72rem 1rem;
            font-size: .88rem;
            font-family: Vazirmatn, sans-serif;
            transition: border .2s, box-shadow .2s;
            outline: none;
            background: #fafafa;
        }

        .input-field:focus {
            border-color: #0ea372;
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(14,163,114,.12);
        }

        .input-field.is-invalid { border-color: #ef4444; }

        .input-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%) translateY(12px);
            color: #9ca3af;
            font-size: .88rem;
            pointer-events: none;
        }

        /* دکمه اصلی */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #0b8560, #0ea372);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: .85rem;
            font-size: .95rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .25s;
            font-family: Vazirmatn, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(11,133,96,.3);
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent);
            transition: left .5s;
        }

        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(11,133,96,.35); }
        .btn-submit:hover::before { left: 100%; }
        .btn-submit:active { transform: translateY(0); }

        /* کارت ادمین */
        .admin-card {
            background: #f0fdf8;
            border: 1.5px solid #bbf7d0;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .85rem;
            margin-top: 1.25rem;
            transition: all .2s;
            cursor: pointer;
        }

        .admin-card:hover {
            background: #dcfce7;
            border-color: #0b8560;
            transform: translateX(3px);
        }

        .admin-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #0b8560, #0ea372);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: .95rem;
            flex-shrink: 0;
        }

        /* لینک فراموشی */
        .forgot-link {
            color: #0b8560;
            font-size: .78rem;
            font-weight: 700;
            text-decoration: none;
            transition: opacity .2s;
        }

        .forgot-link:hover { opacity: .7; }

        /* toggle رمز */
        .toggle-pass {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%) translateY(12px);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            font-size: .85rem;
            transition: color .2s;
        }

        .toggle-pass:hover { color: #0b8560; }

        /* سمت چپ — اطلاعاتی */
        .info-panel {
            color: rgba(255,255,255,.88);
            animation: fadeIn .7s ease both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .info-feature {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            margin-bottom: 1.5rem;
            animation: slideInRight .5s ease both;
        }

        .info-feature:nth-child(1) { animation-delay: .15s; }
        .info-feature:nth-child(2) { animation-delay: .25s; }
        .info-feature:nth-child(3) { animation-delay: .35s; }
        .info-feature:nth-child(4) { animation-delay: .45s; }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(24px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .info-feature-icon {
            width: 44px; height: 44px;
            background: rgba(255,255,255,.12);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            border: 1px solid rgba(255,255,255,.18);
        }

        .info-feature-title { font-weight: 700; font-size: .9rem; color: #fff; margin-bottom: .15rem; }
        .info-feature-sub   { font-size: .78rem; opacity: .72; }

        /* ─── error messages ─── */
        .err-msg {
            color: #ef4444;
            font-size: .75rem;
            margin-top: .3rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        /* responsive */
        @media (max-width: 991px) {
            .info-panel { display: none !important; }
        }
    </style>
@endpush

@section('content')
    <div class="login-page">

        {{-- ─── حلقه‌های پس‌زمینه ─── --}}
        <div class="bg-ring" style="width:600px;height:600px;top:-200px;right:-200px"></div>
        <div class="bg-ring" style="width:400px;height:400px;bottom:-150px;left:-100px;animation-delay:-4s"></div>
        <div class="bg-ring" style="width:260px;height:260px;top:40%;left:20%;animation-delay:-2s"></div>

        {{-- ذرات شناور --}}
        @for($p = 0; $p < 12; $p++)
            <div class="particle" style="
        width: {{ rand(6,20) }}px; height: {{ rand(6,20) }}px;
        left: {{ rand(0,100) }}%;
        bottom: {{ rand(-10,0) }}%;
        animation-duration: {{ rand(10,22) }}s;
        animation-delay: -{{ rand(0,15) }}s;
    "></div>
        @endfor

        {{-- آیکون‌های شناور --}}
        @php $floatIcons = ['💊','💉','🧪','🩺','🌿','⚕️','🔬','🏥']; @endphp
        @for($fi = 0; $fi < 8; $fi++)
            <div class="float-icon" style="
        left: {{ rand(5,90) }}%;
        top: {{ rand(5,90) }}%;
        animation-duration: {{ rand(10,18) }}s;
        animation-delay: -{{ rand(0,12) }}s;
        font-size: {{ rand(14,28) }}px;
    ">{{ $floatIcons[$fi % count($floatIcons)] }}</div>
        @endfor

        <div class="container" style="position:relative;z-index:2">
            <div class="row align-items-center justify-content-center g-5">

                {{-- ─── پنل اطلاعاتی سمت چپ ─── --}}
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="info-panel">
                        <div style="font-size:.78rem;font-weight:700;letter-spacing:.1em;
                                opacity:.7;text-transform:uppercase;margin-bottom:1rem">
                            <i class="fas fa-pills me-1"></i> فارمی‌شاپ
                        </div>
                        <h2 style="font-weight:800;color:#fff;font-size:2rem;line-height:1.35;margin-bottom:1.5rem">
                            خرید دارو<br>
                            <span style="color:#a7f3d0">هوشمندانه‌تر</span>
                        </h2>

                        <div class="info-feature">
                            <div class="info-feature-icon"><i class="fas fa-certificate"></i></div>
                            <div>
                                <div class="info-feature-title">ضمانت اصالت</div>
                                <div class="info-feature-sub">تمام محصولات دارای مجوز وزارت بهداشت</div>
                            </div>
                        </div>

                        <div class="info-feature">
                            <div class="info-feature-icon"><i class="fas fa-truck-fast"></i></div>
                            <div>
                                <div class="info-feature-title">ارسال سریع</div>
                                <div class="info-feature-sub">تحویل در کمترین زمان به سراسر ایران</div>
                            </div>
                        </div>

                        <div class="info-feature">
                            <div class="info-feature-icon"><i class="fas fa-headset"></i></div>
                            <div>
                                <div class="info-feature-title">پشتیبانی ۲۴ ساعته</div>
                                <div class="info-feature-sub">کارشناسان دارویی همیشه در دسترس</div>
                            </div>
                        </div>

                        <div class="info-feature">
                            <div class="info-feature-icon"><i class="fas fa-percent"></i></div>
                            <div>
                                <div class="info-feature-title">تخفیف ویژه اول</div>
                                <div class="info-feature-sub">۱۰٪ تخفیف در اولین خرید با کد PHARMA10</div>
                            </div>
                        </div>

                        {{-- اعداد اعتمادساز --}}
                        <div class="d-flex gap-4 mt-4 pt-3"
                             style="border-top:1px solid rgba(255,255,255,.15)">
                            @foreach([['۵۰K+','مشتری'],['۱۲K+','دارو'],['۹۹%','رضایت']] as $s)
                                <div>
                                    <div style="font-size:1.3rem;font-weight:800;color:#fff">{{ $s[0] }}</div>
                                    <div style="font-size:.72rem;opacity:.65">{{ $s[1] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ─── کارت فرم ─── --}}
                <div class="col-lg-5 col-md-8 col-sm-10">
                    <div class="login-card">

                        {{-- سربرگ --}}
                        <div class="card-header-custom">
                            <div class="header-logo">💊</div>
                            <h4>فارمی‌شاپ</h4>
                            <p>داروخانه آنلاین — ورود به حساب</p>
                        </div>

                        {{-- موج پایین سربرگ --}}
                        <div class="card-wave"></div>

                        {{-- تب‌ها --}}
                        <div class="tabs-wrap">
                            <button class="tab-btn active" id="tabLogin" onclick="switchTab('login')">
                                <i class="fas fa-sign-in-alt"></i> ورود
                            </button>
                            <button class="tab-btn" id="tabRegister" onclick="switchTab('register')">
                                <i class="fas fa-user-plus"></i> ثبت‌نام
                            </button>
                        </div>

                        {{-- ─── فرم ورود ─── --}}
                        <div class="form-wrap" id="loginPane">
                            @if($errors->has('email') && request()->has('_register') === false)
                                <div class="alert alert-danger border-0 rounded-3 py-2 mb-3"
                                     style="font-size:.82rem">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $errors->first('email') }}
                                </div>
                            @endif

                            <form action="{{ route('login') }}" method="POST">
                                @csrf

                                <div class="input-group-custom">
                                    <label>آدرس ایمیل</label>
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" name="email" class="input-field"
                                           placeholder="example@email.com"
                                           value="{{ old('email') }}" required>
                                </div>

                                <div class="input-group-custom">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label style="margin-bottom:0">رمز عبور</label>
                                        <a href="{{ route('password.request') }}" class="forgot-link">
                                            فراموشی رمز؟
                                        </a>
                                    </div>
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" name="password" class="input-field"
                                           placeholder="رمز عبور خود را وارد کنید"
                                           id="loginPass" required>
                                    <button type="button" class="toggle-pass" onclick="togglePass('loginPass','eyeLogin')">
                                        <i class="fas fa-eye" id="eyeLogin"></i>
                                    </button>
                                </div>

                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <input type="checkbox" name="remember" id="rememberMe"
                                           style="width:16px;height:16px;accent-color:#0b8560">
                                    <label for="rememberMe"
                                           style="font-size:.8rem;color:#6b7280;cursor:pointer;margin:0">
                                        مرا به خاطر بسپار
                                    </label>
                                </div>

                                <button type="submit" class="btn-submit">
                                    <i class="fas fa-sign-in-alt"></i>
                                    ورود به حساب
                                </button>
                            </form>

                            {{-- لینک پنل ادمین --}}
                            <a href="{{ route('admin.dashboard') }}" class="admin-card text-decoration-none">
                                <div class="admin-icon">
                                    <i class="fas fa-shield-halved"></i>
                                </div>
                                <div>
                                    <div style="font-size:.82rem;font-weight:700;color:#065f46">
                                        ورود به پنل مدیریت
                                    </div>
                                    <div style="font-size:.73rem;color:#5a7a6e">
                                        برای ادمین‌ها و کارشناسان توزیع
                                    </div>
                                </div>
                                <i class="fas fa-arrow-left ms-auto" style="color:#0b8560;font-size:.82rem"></i>
                            </a>
                        </div>

                        {{-- ─── فرم ثبت‌نام ─── --}}
                        <div class="form-wrap" id="registerPane" style="display:none">
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <input type="hidden" name="_register" value="1">

                                <div class="input-group-custom">
                                    <label>نام کامل</label>
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" name="name" class="input-field"
                                           placeholder="نام و نام خانوادگی"
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="err-msg"><i class="fas fa-circle-exclamation"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="input-group-custom">
                                    <label>آدرس ایمیل</label>
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" name="email" class="input-field"
                                           placeholder="example@email.com"
                                           value="{{ old('email') }}" required>
                                </div>

                                <div class="input-group-custom">
                                    <label>رمز عبور</label>
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" name="password" class="input-field"
                                           id="regPass"
                                           placeholder="حداقل ۸ کاراکتر" required>
                                    <button type="button" class="toggle-pass" onclick="togglePass('regPass','eyeReg')">
                                        <i class="fas fa-eye" id="eyeReg"></i>
                                    </button>
                                </div>

                                <div class="input-group-custom">
                                    <label>تکرار رمز عبور</label>
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" name="password_confirmation" class="input-field"
                                           placeholder="رمز عبور را تکرار کنید" required>
                                </div>

                                <div class="d-flex align-items-flex-start gap-2 mb-4">
                                    <input type="checkbox" name="terms" id="terms" required
                                           style="width:16px;height:16px;accent-color:#0b8560;margin-top:2px;flex-shrink:0">
                                    <label for="terms"
                                           style="font-size:.78rem;color:#6b7280;cursor:pointer;line-height:1.5;margin:0">
                                        <a href="#" style="color:#0b8560;font-weight:700">شرایط و قوانین</a>
                                        فارمی‌شاپ را خوانده‌ام و می‌پذیرم
                                    </label>
                                </div>

                                <button type="submit" class="btn-submit">
                                    <i class="fas fa-user-plus"></i>
                                    ایجاد حساب کاربری
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- لینک بازگشت --}}
                    <div class="text-center mt-3">
                        <a href="{{ route('shop.home') }}"
                           style="color:rgba(255,255,255,.65);font-size:.8rem;transition:.2s"
                           onmouseover="this.style.color='#fff'"
                           onmouseout="this.style.color='rgba(255,255,255,.65)'">
                            <i class="fas fa-arrow-right me-1"></i> بازگشت به فروشگاه
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /* ── تغییر تب ── */
        function switchTab(tab) {
            const isLogin = tab === 'login';
            document.getElementById('loginPane').style.display    = isLogin ? '' : 'none';
            document.getElementById('registerPane').style.display = isLogin ? 'none' : '';

            document.getElementById('tabLogin').classList.toggle('active', isLogin);
            document.getElementById('tabRegister').classList.toggle('active', !isLogin);
        }

        /* ── نمایش/مخفی رمز ── */
        function togglePass(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.className = isPass ? 'fas fa-eye-slash' : 'fas fa-eye';
        }

        /* ── اگر خطای ثبت‌نام بود، تب را عوض کن ── */
        @if($errors->has('name') || ($errors->has('email') && request()->_register))
        switchTab('register');
        @endif

        /* ── تایمر auto-close برای flash ── */
        setTimeout(() => {
            document.querySelectorAll('.toast.show').forEach(t => {
                bootstrap.Toast.getOrCreateInstance(t).hide();
            });
        }, 4000);
    </script>
@endpush
