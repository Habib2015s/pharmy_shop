@php
    use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'فارمی شاپ')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/vazirmatn@33.0.3/Vazirmatn-font-face.css" rel="stylesheet">

    <style>
        /* ═══════════════ TOKENS ═══════════════ */
        :root {
            --g1: #0b8560;
            --g2: #0ea372;
            --g3: #34d399;
            --g-light: #e6f7f2;
            --g-pale:  #f0fdf8;
            --ink:     #0f1f17;
            --muted:   #5a7a6e;
            --border:  #d1e8df;
            --white:   #ffffff;
            --radius:  16px;
            --nav-h:   68px;
        }

        * { font-family: Vazirmatn, sans-serif; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            background: #f5f8f6;
            color: var(--ink);
            margin: 0;
        }

        a { text-decoration: none; color: inherit; }

        /* ═══════════════ NAVBAR ═══════════════ */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--nav-h);
            z-index: 1000;
            transition: background .35s ease, box-shadow .35s ease, backdrop-filter .35s ease;
            background: transparent;
        }

        /* شفاف در بالا، solid بعد از scroll */
        .navbar.scrolled {
            background: rgba(11, 133, 96, 0.97);
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 24px rgba(0,0,0,.15);
        }

        .navbar.at-top { background: transparent; }

        .navbar-brand {
            color: #fff !important;
            font-weight: 800;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .navbar-brand .brand-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,.2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            transition: background .2s;
        }

        .navbar-brand:hover .brand-icon { background: rgba(255,255,255,.3); }

        .nav-link {
            color: rgba(255,255,255,.88) !important;
            font-size: .88rem;
            font-weight: 600;
            padding: .45rem .85rem !important;
            border-radius: 8px;
            transition: all .2s;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 2px; right: 50%; left: 50%;
            height: 2px;
            background: #fff;
            border-radius: 2px;
            transition: all .25s;
            opacity: 0;
        }

        .nav-link:hover::after,
        .nav-link.active::after { right: 10%; left: 10%; opacity: 1; }

        .nav-link:hover { color: #fff !important; background: rgba(255,255,255,.1); }

        /* دکمه سبد */
        .cart-btn {
            background: rgba(255,255,255,.15);
            border: 1.5px solid rgba(255,255,255,.4);
            color: #fff !important;
            border-radius: 10px;
            padding: .4rem .9rem !important;
            font-size: .85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .45rem;
            transition: all .2s;
        }

        .cart-btn:hover {
            background: rgba(255,255,255,.25);
            border-color: rgba(255,255,255,.6);
        }

        .cart-btn .cart-count {
            background: #ff6b35;
            color: #fff;
            font-size: .65rem;
            width: 18px; height: 18px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
        }

        /* دکمه ورود */
        .login-btn {
            background: #fff;
            color: var(--g1) !important;
            border-radius: 10px;
            padding: .4rem 1rem !important;
            font-size: .85rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: .45rem;
            border: none;
            transition: all .2s;
            box-shadow: 0 2px 10px rgba(0,0,0,.12);
        }

        .login-btn:hover {
            background: var(--g-light);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(0,0,0,.16);
        }

        /* user dropdown */
        .user-btn {
            background: rgba(255,255,255,.15);
            border: 1.5px solid rgba(255,255,255,.4);
            color: #fff !important;
            border-radius: 10px;
            padding: .4rem .85rem !important;
            font-size: .85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .45rem;
            cursor: pointer;
            transition: all .2s;
        }

        .user-btn:hover { background: rgba(255,255,255,.25); }

        .user-avatar {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, var(--g3), var(--g2));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem;
            font-weight: 800;
        }

        /* ═══════════════ SCROLL PROGRESS BAR ═══════════════ */
        #progress-bar {
            position: fixed;
            top: var(--nav-h);
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--g3), #fff);
            z-index: 999;
            width: 0%;
            transition: width .1s linear;
        }

        /* ═══════════════ MAIN CONTENT ═══════════════ */
        main { padding-top: var(--nav-h); }

        /* ═══════════════ CARDS ═══════════════ */
        .medicine-card {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: .25s;
            height: 100%;
        }

        .medicine-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(11,133,96,.12);
            border-color: var(--g3);
        }

        .medicine-image {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 68px;
            background: var(--g-pale);
            position: relative;
            overflow: hidden;
        }

        .medicine-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 70% 30%, rgba(52,211,153,.12), transparent 60%);
        }

        .medicine-body { padding: 18px; }

        .medicine-title { font-weight: 700; margin-bottom: 8px; font-size: .92rem; }

        .medicine-price {
            color: var(--g1);
            font-weight: 800;
            font-size: 1.05rem;
        }

        .btn-add-cart {
            background: var(--g1);
            color: #fff;
            border: none;
            border-radius: 10px;
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem;
            cursor: pointer;
            transition: all .2s;
            flex-shrink: 0;
        }

        .btn-add-cart:hover {
            background: var(--g2);
            transform: scale(1.1);
        }

        /* ═══════════════ SECTION TITLES ═══════════════ */
        .section-eyebrow {
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--g2);
            margin-bottom: .4rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 0;
        }

        /* ═══════════════ ANIMATIONS ═══════════════ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(52,211,153,.4); }
            70%  { box-shadow: 0 0 0 14px rgba(52,211,153,0); }
            100% { box-shadow: 0 0 0 0 rgba(52,211,153,0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-10px); }
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        .anim-fade-up {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .anim-fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ═══════════════ FOOTER ═══════════════ */
        .site-footer {
            background: #0a1c14;
            color: rgba(255,255,255,.65);
            padding: 3rem 0 1.25rem;
            margin-top: 5rem;
        }

        .site-footer h6 { color: #fff; font-weight: 700; margin-bottom: 1rem; }

        .site-footer a {
            color: rgba(255,255,255,.5);
            font-size: .83rem;
            display: block;
            margin-bottom: .35rem;
            transition: color .2s;
        }

        .site-footer a:hover { color: var(--g3); }

        .footer-logo {
            width: 42px; height: 42px;
            background: var(--g1);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.1rem;
        }

        .footer-divider {
            border-color: rgba(255,255,255,.08);
            margin: 2rem 0 1.25rem;
        }

        /* responsive */
        @media (max-width: 767px) {
            .section-title { font-size: 1.2rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, .anim-fade-up { transition: none !important; animation: none !important; }
        }
    </style>

    @stack('styles')

</head>
<body>

{{-- ─── نوار پیشرفت اسکرول ─── --}}
<div id="progress-bar"></div>

{{-- ═══════════════════════════════════
     NAVBAR
═══════════════════════════════════ --}}
<nav class="navbar navbar-expand-lg at-top" id="mainNav">
    <div class="container">

        {{-- لوگو --}}
        <a class="navbar-brand" href="{{ route('shop.home') }}">
            <div class="brand-icon"><i class="fas fa-pills"></i></div>
            فارمی‌شاپ
        </a>

        <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#navMenu"
                style="color:#fff">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.home') ? 'active' : '' }}"
                       href="{{ route('shop.home') }}">
                        <i class="fas fa-home me-1"></i> خانه
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.products') ? 'active' : '' }}"
                       href="{{ route('shop.products') }}">
                        <i class="fas fa-th me-1"></i> داروها
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-tags me-1"></i> دسته‌بندی
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark border-0 shadow"
                        style="background:#0b8560;border-radius:12px;min-width:180px">
                        @foreach(\App\Models\Category::where('is_active',true)->take(8)->get() as $c)
                            <li>
                                <a class="dropdown-item" href="{{ route('shop.products', ['category'=>$c->slug]) }}"
                                   style="font-size:.85rem;padding:.5rem 1rem">
                                    {{ $c->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>

            {{-- actions --}}
            <div class="d-flex align-items-center gap-2">

                {{-- جستجو کوچک --}}
                <form action="{{ route('shop.search') }}" method="GET"
                      class="d-none d-lg-flex align-items-center"
                      style="background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.25);
                             border-radius:10px;overflow:hidden">
                    <input type="text" name="q" placeholder="جستجو..."
                           value="{{ request('q') }}"
                           style="background:transparent;border:none;color:#fff;padding:.38rem .8rem;
                                  font-size:.82rem;width:160px;outline:none"
                           class="text-white">
                    <button style="background:transparent;border:none;color:#fff;padding:.38rem .7rem"
                            type="submit">
                        <i class="fas fa-search" style="font-size:.8rem"></i>
                    </button>
                </form>

                {{-- سبد خرید --}}
                <a href="{{ route('shop.cart') }}" class="cart-btn nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    @php $cartCount = collect(session('cart', []))->sum('qty') @endphp
                    @if($cartCount > 0)
                        <span class="cart-count">{{ $cartCount }}</span>
                    @endif
                    <span class="d-none d-lg-inline">سبد</span>
                </a>

                {{-- ورود / پروفایل --}}
                @guest
                    <a href="{{ route('shop.login') }}" class="login-btn nav-link">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>ورود</span>
                    </a>
                @else
                    <div class="dropdown">
                        <div class="user-btn" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="d-none d-lg-inline">{{ Str::limit(auth()->user()->name, 10) }}</span>
                            <i class="fas fa-chevron-down" style="font-size:.65rem;opacity:.7"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-start shadow border-0"
                            style="border-radius:12px;min-width:200px;margin-top:.5rem">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-700" style="font-size:.85rem">{{ auth()->user()->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ auth()->user()->email }}</div>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('shop.orders') }}">
                                    <i class="fas fa-box me-2 text-muted"></i>سفارش‌های من
                                </a>
                            </li>
                            @can('manage medicines')
                                <li>
                                    <a class="dropdown-item py-2 text-success fw-700"
                                       href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>پنل مدیریت
                                    </a>
                                </li>
                            @endcan
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger py-2">
                                        <i class="fas fa-sign-out-alt me-2"></i>خروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- ─── Flash messages ─── --}}
@if(session('success') || session('cart_success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index:9999;margin-top:80px">
        <div class="toast show align-items-center text-white border-0 shadow"
             style="background:var(--g1);border-radius:12px" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') ?? session('cart_success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endif

<main>
    @yield('content')
</main>

{{-- ═══════════════════════════════════
     FOOTER
═══════════════════════════════════ --}}
<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="footer-logo"><i class="fas fa-pills"></i></div>
                    <div>
                        <div style="color:#fff;font-weight:800;font-size:1.05rem">فارمی‌شاپ</div>
                        <div style="font-size:.7rem">داروخانه آنلاین معتبر</div>
                    </div>
                </div>
                <p style="font-size:.82rem;line-height:1.9">
                    پلتفرم خرید آنلاین دارو با ضمانت اصالت کالا و پشتیبانی ۲۴ ساعته.
                    همه محصولات دارای مجوز وزارت بهداشت.
                </p>
                <div class="d-flex gap-2 mt-3">
                    @foreach([['fab fa-instagram','#e1306c'],['fab fa-telegram','#0088cc'],['fab fa-twitter','#1da1f2']] as $soc)
                        <a href="#" class="d-flex align-items-center justify-content-center rounded-circle"
                           style="width:34px;height:34px;background:rgba(255,255,255,.08);
                              color:rgba(255,255,255,.65);font-size:.85rem;transition:.2s"
                           onmouseover="this.style.background='{{ $soc[1] }}';this.style.color='#fff'"
                           onmouseout="this.style.background='rgba(255,255,255,.08)';this.style.color='rgba(255,255,255,.65)'">
                            <i class="{{ $soc[0] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6>دسترسی سریع</h6>
                <a href="{{ route('shop.home') }}">صفحه اصلی</a>
                <a href="{{ route('shop.products') }}">همه داروها</a>
                <a href="#">درباره ما</a>
                <a href="#">تماس با ما</a>
                <a href="#">سوالات متداول</a>
            </div>
            <div class="col-lg-2 col-6">
                <h6>دسته‌بندی‌ها</h6>
                @foreach(\App\Models\Category::where('is_active',true)->take(5)->get() as $fc)
                    <a href="{{ route('shop.products', ['category'=>$fc->slug]) }}">{{ $fc->name }}</a>
                @endforeach
            </div>
            <div class="col-lg-4 col-md-6">
                <h6>تماس با ما</h6>
                <div class="d-flex flex-column gap-2" style="font-size:.82rem">
                    <div><i class="fas fa-phone me-2" style="color:var(--g3)"></i>۰۲۱–۱۲۳۴۵۶۷۸</div>
                    <div><i class="fas fa-envelope me-2" style="color:var(--g3)"></i>info@farmishop.ir</div>
                    <div><i class="fas fa-map-marker-alt me-2" style="color:var(--g3)"></i>تهران، خیابان ولیعصر</div>
                    <div><i class="fas fa-clock me-2" style="color:var(--g3)"></i>پشتیبانی: ۲۴ ساعته</div>
                </div>
                <div class="mt-3 rounded-2 p-2"
                     style="background:rgba(255,255,255,.06);font-size:.75rem;border:1px solid rgba(255,255,255,.1)">
                    <i class="fas fa-certificate me-1" style="color:var(--g3)"></i>
                    مجوز فروش اینترنتی از وزارت بهداشت
                </div>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2"
             style="font-size:.78rem">
            <span>© {{ date('Y') }} فارمی‌شاپ — تمام حقوق محفوظ است</span>
            <div class="d-flex gap-3">
                <a href="#">حریم خصوصی</a>
                <a href="#">شرایط استفاده</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /* ── navbar scroll ── */
    const nav   = document.getElementById('mainNav');
    const pbar  = document.getElementById('progress-bar');

    window.addEventListener('scroll', () => {
        const sy   = window.scrollY;
        const dh   = document.documentElement.scrollHeight - window.innerHeight;
        const pct  = dh > 0 ? (sy / dh) * 100 : 0;

        pbar.style.width = pct + '%';

        if (sy > 60) {
            nav.classList.remove('at-top');
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
            nav.classList.add('at-top');
        }
    }, { passive: true });

    /* ── intersection observer برای fade-up ── */
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.anim-fade-up').forEach(el => obs.observe(el));

    /* ── auto-hide toast ── */
    setTimeout(() => {
        document.querySelectorAll('.toast.show').forEach(t => {
            bootstrap.Toast.getOrCreateInstance(t).hide();
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>
