{{-- resources/views/shop/home/index.blade.php --}}
@extends('layout.app')
@section('title', 'فارمی‌شاپ — داروخانه آنلاین')


@push('styles')
    <style>
        /* ═══ HERO ═══ */
        .hero-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 5rem 0 4rem;
        }

        /* پس‌زمینه چند لایه که با اسکرول عوض می‌شود */
        .hero-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #0b8560 0%, #0ea372 55%, #34d399 100%);
            transition: opacity .6s ease;
            z-index: 0;
        }

        /* لایه دوم — آبی بنفش */
        .hero-wrap::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #1a237e 0%, #1565c0 50%, #00acc1 100%);
            opacity: 0;
            transition: opacity .6s ease;
            z-index: 0;
        }

        .hero-wrap.phase-2::after  { opacity: 1; }

        /* حلقه‌های دکوراتیف */
        .hero-ring {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,.12);
            pointer-events: none;
            z-index: 1;
            animation: ring-pulse 4s ease-in-out infinite;
        }

        @keyframes ring-pulse {
            0%, 100% { transform: scale(1); opacity: .12; }
            50%       { transform: scale(1.04); opacity: .22; }
        }

        .hero-content { position: relative; z-index: 2; }

        /* pill-badge */
        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.3);
            color: #fff;
            font-size: .78rem;
            font-weight: 700;
            padding: .35rem 1rem;
            border-radius: 30px;
            backdrop-filter: blur(6px);
            margin-bottom: 1.4rem;
            animation: fadeIn .6s ease both;
        }

        .hero-pill .dot {
            width: 7px; height: 7px;
            background: #34d399;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: .25; }
        }

        .hero-h1 {
            font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.3;
            margin-bottom: 1.2rem;
            animation: fadeUp .7s ease both .1s;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .hero-sub {
            color: rgba(255,255,255,.82);
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
            animation: fadeUp .7s ease both .2s;
        }

        .hero-ctas {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
            animation: fadeUp .7s ease both .3s;
        }

        .btn-hero-main {
            background: #fff;
            color: #0b8560;
            font-weight: 800;
            font-size: .92rem;
            padding: .75rem 1.8rem;
            border-radius: 12px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: all .25s;
            box-shadow: 0 6px 24px rgba(0,0,0,.15);
        }

        .btn-hero-main:hover {
            background: #f0fdf8;
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(0,0,0,.2);
            color: #0b8560;
        }

        .btn-hero-ghost {
            background: rgba(255,255,255,.12);
            color: #fff;
            font-weight: 700;
            font-size: .88rem;
            padding: .72rem 1.5rem;
            border-radius: 12px;
            border: 1.5px solid rgba(255,255,255,.4);
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: all .25s;
            backdrop-filter: blur(4px);
        }

        .btn-hero-ghost:hover {
            background: rgba(255,255,255,.22);
            color: #fff;
            transform: translateY(-2px);
        }

        /* آمار زیر دکمه‌ها */
        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2.4rem;
            animation: fadeUp .7s ease both .4s;
        }

        .hero-stat-num {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .hero-stat-label {
            font-size: .72rem;
            color: rgba(255,255,255,.7);
            margin-top: .2rem;
        }

        /* ایموجی دکوراتیف سمت چپ */
        .hero-illustration {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn .8s ease both .3s;
        }

        .hero-big-icon {
            font-size: 8rem;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,.25));
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(-4deg); }
            50%       { transform: translateY(-16px) rotate(4deg); }
        }

        /* حلقه دور آیکون */
        .icon-ring {
            position: absolute;
            width: 240px; height: 240px;
            border: 2px dashed rgba(255,255,255,.18);
            border-radius: 50%;
            animation: spin-slow 18s linear infinite;
        }

        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* ─── داروهای شناور دور آیکون ─── */
        .orbit-item {
            position: absolute;
            background: rgba(255,255,255,.18);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 12px;
            padding: .45rem .8rem;
            font-size: .72rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: .35rem;
            white-space: nowrap;
            animation: float 3s ease-in-out infinite;
        }

        .orbit-item:nth-child(2) { animation-delay: -.8s; top: 5%; right: 60%; }
        .orbit-item:nth-child(3) { animation-delay: -1.6s; bottom: 10%; right: 55%; }
        .orbit-item:nth-child(4) { animation-delay: -2.4s; top: 20%; left: 5%; }

        /* ═══ SCROLL SECTION BG TRANSITION ═══ */
        /* Section ها با کلاس‌های مختلف پس‌زمینه چرخش می‌کنند */
        .section-scroll {
            position: relative;
            overflow: hidden;
        }

        /* ─── نوار اسکرول خودکار (marquee داروها) ─── */
        .marquee-wrap {
            overflow: hidden;
            background: var(--g1);
            padding: .65rem 0;
        }

        .marquee-track {
            display: flex;
            gap: 2rem;
            animation: marquee 22s linear infinite;
            width: max-content;
        }

        @keyframes marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }

        .marquee-item {
            color: rgba(255,255,255,.8);
            font-size: .78rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .5rem;
            white-space: nowrap;
        }

        .marquee-item i { color: #34d399; }

        /* ═══ دسته‌بندی‌ها ═══ */
        .cat-card {
            border-radius: 16px;
            padding: 1.5rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all .25s;
            border: 2px solid transparent;
            display: block;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .cat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity .25s;
            border-radius: 14px;
            background: radial-gradient(circle at center, rgba(255,255,255,.4), transparent 70%);
        }

        .cat-card:hover { transform: translateY(-5px) scale(1.03); }
        .cat-card:hover::before { opacity: 1; }

        .cat-icon { font-size: 2.2rem; margin-bottom: .6rem; display: block; }
        .cat-name { font-weight: 700; font-size: .85rem; }
        .cat-count { font-size: .72rem; margin-top: .2rem; opacity: .7; }

        /* ═══ کارت دارو ═══ */
        .med-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: .65rem;
            font-weight: 800;
            padding: .2rem .55rem;
            border-radius: 20px;
        }

        /* ═══ بنر میانی — scroll parallax ═══ */
        .mid-banner {
            border-radius: 20px;
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0b8560, #0ea372 60%, #2dd4a7);
            color: #fff;
        }

        .mid-banner::before {
            content: '';
            position: absolute;
            width: 320px; height: 320px;
            background: rgba(255,255,255,.07);
            border-radius: 50%;
            top: -80px; left: -80px;
            pointer-events: none;
        }

        .mid-banner::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
            bottom: -60px; right: 100px;
            pointer-events: none;
        }

        /* ═══ TRUST / WHY ═══ */
        .why-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.75rem 1.5rem;
            border: 1px solid #e6f7f2;
            transition: all .25s;
        }

        .why-card:hover {
            border-color: #34d399;
            box-shadow: 0 10px 30px rgba(11,133,96,.1);
            transform: translateY(-3px);
        }

        .why-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        /* ═══ background transition با scroll ═══ */
        /* هر section پس‌زمینه متفاوتی دارد */
        body { transition: background-color .5s ease; }
    </style>
@endpush

@section('content')

    {{-- ══════════════════════════════════════════════
         HERO — تمام صفحه، با انیمیشن scroll
    ══════════════════════════════════════════════ --}}
    <section class="hero-wrap" id="heroSection">

        {{-- حلقه‌های دکوراتیف --}}
        <div class="hero-ring" style="width:500px;height:500px;top:-100px;left:-150px;animation-delay:0s"></div>
        <div class="hero-ring" style="width:350px;height:350px;bottom:-80px;right:100px;animation-delay:-2s"></div>
        <div class="hero-ring" style="width:200px;height:200px;top:30%;left:40%;animation-delay:-4s"></div>

        <div class="container hero-content">
            <div class="row align-items-center g-5">

                {{-- متن --}}
                <div class="col-lg-6">
                    <div class="hero-pill">
                        <span class="dot"></span>
                        داروخانه آنلاین رسمی — مجوز وزارت بهداشت
                    </div>

                    <h1 class="hero-h1">
                        سلامتی شما<br>
                        <span style="color:#a7f3d0">اولویت ماست</span>
                    </h1>

                    <p class="hero-sub">
                        هزاران نوع دارو و مکمل با ضمانت اصالت،
                        مستقیم از توزیع‌کننده رسمی — ارسال سریع به سراسر ایران.
                    </p>

                    <div class="hero-ctas">
                        <a href="{{ route('shop.products') }}" class="btn-hero-main">
                            <i class="fas fa-search"></i> مشاهده داروها
                        </a>
                        <a href="{{ route('shop.login') }}" class="btn-hero-ghost">
                            <i class="fas fa-sign-in-alt"></i> ورود / ثبت‌نام
                        </a>
                    </div>

                    <div class="hero-stats">
                        @foreach([['۱۲,۰۰۰+','دارو موجود'],['۵۰,۰۰۰+','مشتری'],['۲۴/۷','پشتیبانی']] as $s)
                            <div>
                                <div class="hero-stat-num">{{ $s[0] }}</div>
                                <div class="hero-stat-label">{{ $s[1] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- تصویر / آیکون --}}
                <div class="col-lg-6">
                    <div class="hero-illustration" style="height:340px">
                        <div class="icon-ring"></div>

                        {{-- داروهای شناور --}}
                        <div class="orbit-item" style="top:8%;left:50%">
                            <i class="fas fa-capsules text-success"></i> آنتی‌بیوتیک
                        </div>
                        <div class="orbit-item" style="bottom:12%;left:45%">
                            <i class="fas fa-heart-pulse"></i> قلب و عروق
                        </div>
                        <div class="orbit-item" style="top:35%;right:2%">
                            <i class="fas fa-syringe text-info"></i> واکسن
                        </div>

                        <div class="hero-big-icon">💊</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- فلش اسکرول --}}
        <div class="position-absolute bottom-0 start-50 translate-middle-x pb-3 text-center"
             style="z-index:2;animation:float 2s ease-in-out infinite">
            <div style="color:rgba(255,255,255,.6);font-size:.72rem;margin-bottom:.3rem">اسکرول کنید</div>
            <i class="fas fa-chevron-down" style="color:rgba(255,255,255,.5);font-size:1.1rem"></i>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         MARQUEE — نوار داروها
    ══════════════════════════════════════════════ --}}
    <div class="marquee-wrap">
        <div class="marquee-track">
            @php
                $items = ['آموکسی‌سیلین','ایبوپروفن','آتورواستاتین','ویتامین D3','متفورمین','امپرازول','سرترالین','آلپرازولام','لوواستاتین','ایندومتاسین'];
                $icons = ['fa-pills','fa-capsules','fa-tablets','fa-syringe','fa-vials','fa-prescription-bottle','fa-mortar-pestle','fa-flask','fa-lungs','fa-heartbeat'];
                $doubled = array_merge($items, $items); // برای loop بی‌درز
            @endphp
            @foreach($doubled as $i => $drug)
                <div class="marquee-item">
                    <i class="fas {{ $icons[$i % count($icons)] }}"></i>
                    {{ $drug }}
                </div>
            @endforeach
        </div>
    </div>


    {{-- ══════════════════════════════════════════════
         دسته‌بندی‌ها
    ══════════════════════════════════════════════ --}}
    <section class="py-5 section-scroll" id="sectionCat" style="background:#f5f8f6">
        <div class="container">
            <div class="text-center mb-4 anim-fade-up">
                <div class="section-eyebrow"><i class="fas fa-tags me-1"></i> دسته‌بندی‌ها</div>
                <h2 class="section-title">دارو می‌خوای؟ از کجا شروع کنی</h2>
            </div>

            @php
                $catMeta = [
                    'antibiotic'       => ['🦠','#fef3c7','#92400e'],
                    'cardiovascular'   => ['❤️','#fce7f3','#9d174d'],
                    'diabetes'         => ['🩸','#ede9fe','#5b21b6'],
                    'gastrointestinal' => ['🫁','#dcfce7','#166534'],
                    'analgesic'        => ['💊','#e0f2fe','#075985'],
                    'vitamin'          => ['🌿','#ecfdf5','#065f46'],
                    'neurological'     => ['🧠','#f3e8ff','#6b21a8'],
                    'respiratory'      => ['🌬️','#cffafe','#155e75'],
                ];
            @endphp

            <div class="row g-3">
                @foreach($categories as $cat)
                    @php
                        [$ico, $bg, $tc] = $catMeta[$cat->slug] ?? ['💊','#f0fdf4','#166534'];
                    @endphp
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 anim-fade-up">
                        <a href="{{ route('shop.products', ['category'=>$cat->slug]) }}"
                           class="cat-card"
                           style="background:{{ $bg }};color:{{ $tc }}">
                            <span class="cat-icon">{{ $ico }}</span>
                            <div class="cat-name">{{ $cat->name }}</div>
                            <div class="cat-count">{{ $cat->activeMedicinesCount() }} دارو</div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         پرفروش‌ترین داروها
    ══════════════════════════════════════════════ --}}
    <section class="py-5 section-scroll" id="sectionFeatured" style="background:#fff">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4 anim-fade-up">
                <div>
                    <div class="section-eyebrow"><i class="fas fa-fire me-1"></i> پرفروش‌ترین‌ها</div>
                    <h2 class="section-title">انتخاب هزاران مشتری</h2>
                </div>
                <a href="{{ route('shop.products') }}"
                   class="text-success fw-700 d-none d-md-inline"
                   style="font-size:.85rem">
                    همه محصولات <i class="fas fa-arrow-left ms-1"></i>
                </a>
            </div>

            <div class="row g-3">
                @foreach($featuredMedicines as $med)
                    <div class="col-6 col-md-4 col-lg-3 anim-fade-up">
                        <div class="medicine-card">
                            <div class="medicine-image" style="position:relative">
                                @if($med->image)
                                    <img src="{{ asset('storage/'.$med->image) }}"
                                         style="height:160px;object-fit:contain;padding:.5rem">
                                @else
                                    <span>💊</span>
                                @endif
                                <span class="med-badge badge bg-success">پرفروش</span>
                            </div>
                            <div class="medicine-body">
                                <div class="badge text-bg-light mb-1" style="font-size:.68rem;border:1px solid #e5e7eb">
                                    {{ $med->category->name }}
                                </div>
                                <div class="medicine-title">{{ $med->name }}</div>
                                <div class="text-muted mb-2" style="font-size:.75rem">{{ $med->generic_name }}</div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="medicine-price">{{ number_format($med->sale_price) }}</div>
                                        <small class="text-muted">تومان</small>
                                    </div>
                                    @if($med->stock > 0)
                                        <form action="{{ route('shop.cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn-add-cart" title="افزودن به سبد">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-danger" style="font-size:.68rem">ناموجود</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4 d-md-none anim-fade-up">
                <a href="{{ route('shop.products') }}" class="btn btn-outline-success">همه داروها</a>
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         بنر میانی
    ══════════════════════════════════════════════ --}}
    <section class="py-4 section-scroll" id="sectionBanner" style="background:#f0fdf8">
        <div class="container">
            <div class="mid-banner anim-fade-up">
                <div class="row align-items-center g-4" style="position:relative;z-index:2">
                    <div class="col-md-8">
                        <div style="font-size:.8rem;font-weight:700;opacity:.8;letter-spacing:.08em;
                                text-transform:uppercase;margin-bottom:.5rem">
                            <i class="fas fa-tag me-1"></i> پیشنهاد ویژه
                        </div>
                        <h3 style="font-weight:800;color:#fff;margin-bottom:.5rem;font-size:1.5rem">
                            🎁 اولین سفارش با ۱۰٪ تخفیف!
                        </h3>
                        <p style="opacity:.85;font-size:.88rem;margin-bottom:0">
                            با کد <strong style="background:rgba(255,255,255,.2);padding:.1rem .5rem;border-radius:6px">
                                PHARMA10</strong> در اولین خرید استفاده کنید
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('shop.products') }}"
                           style="background:#fff;color:#0b8560;font-weight:800;padding:.75rem 2rem;
                              border-radius:12px;display:inline-flex;align-items:center;gap:.5rem;
                              box-shadow:0 6px 24px rgba(0,0,0,.15);transition:all .2s;font-size:.9rem"
                           onmouseover="this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.transform=''">
                            <i class="fas fa-bolt"></i> خرید کن!
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         جدیدترین‌ها
    ══════════════════════════════════════════════ --}}
    <section class="py-5 section-scroll" id="sectionNew" style="background:#fff">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4 anim-fade-up">
                <div>
                    <div class="section-eyebrow"><i class="fas fa-sparkles me-1"></i> تازه‌وارد</div>
                    <h2 class="section-title">داروهای جدید</h2>
                </div>
            </div>
            <div class="row g-3">
                @foreach($newMedicines as $med)
                    <div class="col-6 col-md-4 col-lg-3 anim-fade-up">
                        <div class="medicine-card">
                            <div class="medicine-image" style="position:relative">
                                @if($med->image)
                                    <img src="{{ asset('storage/'.$med->image) }}"
                                         style="height:160px;object-fit:contain;padding:.5rem">
                                @else
                                    <span>💉</span>
                                @endif
                                <span class="med-badge badge bg-primary">جدید</span>
                            </div>
                            <div class="medicine-body">
                                <div class="badge text-bg-light mb-1"
                                     style="font-size:.68rem;border:1px solid #e5e7eb">
                                    {{ $med->category->name }}
                                </div>
                                <div class="medicine-title">{{ $med->name }}</div>
                                <div class="text-muted mb-2" style="font-size:.75rem">{{ $med->generic_name }}</div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="medicine-price">{{ number_format($med->sale_price) }}</div>
                                        <small class="text-muted">تومان</small>
                                    </div>
                                    @if($med->stock > 0)
                                        <form action="{{ route('shop.cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn-add-cart">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         چرا فارمی‌شاپ؟
    ══════════════════════════════════════════════ --}}
    <section class="py-5 section-scroll" id="sectionWhy"
             style="background:linear-gradient(180deg,#f0fdf8 0%,#fff 100%)">
        <div class="container">
            <div class="text-center mb-5 anim-fade-up">
                <div class="section-eyebrow"><i class="fas fa-shield-alt me-1"></i> چرا ما؟</div>
                <h2 class="section-title">مزایای خرید از فارمی‌شاپ</h2>
            </div>
            <div class="row g-3">
                @foreach([
                    ['fas fa-certificate',     '#dcfce7','#166534', 'اصالت کالا',     'همه محصولات دارای مجوز از وزارت بهداشت'],
                    ['fas fa-truck-fast',      '#e0f2fe','#075985', 'ارسال سریع',     'تحویل در کمترین زمان به سراسر ایران'],
                    ['fas fa-undo-alt',        '#fce7f3','#9d174d', 'بازگشت ۷ روزه', 'ضمانت برگشت کالا بدون هیچ سوالی'],
                    ['fas fa-headset',         '#f3e8ff','#6b21a8', 'پشتیبانی ۲۴/۷', 'کارشناسان ما همیشه آماده پاسخگویی'],
                    ['fas fa-percent',         '#fef3c7','#92400e', 'قیمت مناسب',    'مستقیم از توزیع‌کننده، بدون واسطه'],
                    ['fas fa-lock',            '#ecfdf5','#065f46', 'پرداخت امن',    'درگاه بانکی معتبر با SSL 256bit'],
                ] as $why)
                    <div class="col-md-6 col-lg-4 anim-fade-up">
                        <div class="why-card">
                            <div class="why-icon" style="background:{{ $why[1] }};color:{{ $why[2] }}">
                                <i class="{{ $why[0] }}"></i>
                            </div>
                            <h6 class="fw-700 mb-1">{{ $why[3] }}</h6>
                            <p class="text-muted mb-0" style="font-size:.83rem">{{ $why[4] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════
         CTA پایین صفحه
    ══════════════════════════════════════════════ --}}
    <section class="py-5 section-scroll" id="sectionCta"
             style="background:linear-gradient(135deg,#0b8560,#0ea372)">
        <div class="container text-center anim-fade-up" style="position:relative;z-index:1">
            <div style="font-size:2.5rem;margin-bottom:1rem">🌿</div>
            <h2 style="color:#fff;font-weight:800;font-size:1.8rem;margin-bottom:.75rem">
                آماده شروع هستی؟
            </h2>
            <p style="color:rgba(255,255,255,.8);margin-bottom:2rem">
                ثبت‌نام کن و اولین سفارشت رو با ۱۰٪ تخفیف بده
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('shop.login') }}"
                   style="background:#fff;color:#0b8560;font-weight:800;padding:.85rem 2.2rem;
                      border-radius:12px;display:inline-flex;align-items:center;gap:.6rem;
                      box-shadow:0 8px 28px rgba(0,0,0,.18);font-size:.95rem;transition:all .25s"
                   onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 14px 36px rgba(0,0,0,.22)'"
                   onmouseout="this.style.transform='';this.style.boxShadow='0 8px 28px rgba(0,0,0,.18)'">
                    <i class="fas fa-user-plus"></i> ثبت‌نام رایگان
                </a>
                <a href="{{ route('shop.products') }}"
                   style="background:rgba(255,255,255,.15);color:#fff;font-weight:700;
                      padding:.85rem 2rem;border-radius:12px;border:1.5px solid rgba(255,255,255,.4);
                      display:inline-flex;align-items:center;gap:.6rem;font-size:.92rem;
                      backdrop-filter:blur(6px);transition:all .25s"
                   onmouseover="this.style.background='rgba(255,255,255,.25)'"
                   onmouseout="this.style.background='rgba(255,255,255,.15)'">
                    <i class="fas fa-pills"></i> مشاهده داروها
                </a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        /* ══════════════════════════════════════════════
           SCROLL-TRIGGERED BG ANIMATION
           هر section یک رنگ به body می‌دهد
        ══════════════════════════════════════════════ */
        const sections = [
            { id: 'sectionCat',      bg: '#f5f8f6' },
            { id: 'sectionFeatured', bg: '#ffffff' },
            { id: 'sectionBanner',   bg: '#f0fdf8' },
            { id: 'sectionNew',      bg: '#ffffff' },
            { id: 'sectionWhy',      bg: '#f8f0fb' },
            { id: 'sectionCta',      bg: '#e6f7f2' },
        ];

        /* ── hero phase change ── */
        const heroSection = document.getElementById('heroSection');

        function onScroll() {
            const sy = window.scrollY;
            const wh = window.innerHeight;

            /* hero phase‌بندی با scroll */
            const heroH = heroSection.offsetHeight;
            if (sy > heroH * .5) {
                heroSection.classList.add('phase-2');
            } else {
                heroSection.classList.remove('phase-2');
            }

            /* body bg بر اساس section فعال */
            let active = null;
            sections.forEach(s => {
                const el = document.getElementById(s.id);
                if (!el) return;
                const rect = el.getBoundingClientRect();
                if (rect.top <= wh * .45) active = s.bg;
            });

            if (active) document.body.style.background = active;
        }

        window.addEventListener('scroll', onScroll, { passive: true });

        /* ── stagger fade-up ── */
        document.querySelectorAll('.anim-fade-up').forEach((el, i) => {
            el.style.transitionDelay = (i % 4) * 80 + 'ms';
        });
    </script>
@endpush
