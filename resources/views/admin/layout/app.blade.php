{{-- ============================================================
     فایل: resources/views/admin/layout/app.blade.php
     توضیح: لایه‌اصلی پنل ادمین - RTL فارسی با Bootstrap 5
     ============================================================ --}}
    <!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'سیستم پخش دارو') | پنل مدیریت</title>

    {{-- Bootstrap 5 RTL --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- Vazirmatn فونت فارسی --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vazirmatn@33.0.3/Vazirmatn-font-face.css">

    <style>
        /* ─── متغیرهای رنگ ─────────────────────────────── */
        :root {
            --primary:   #1a6b3c;   /* سبز داروخانه‌ای */
            --primary-light: #e8f5ee;
            --secondary: #2c7be5;
            --accent:    #f0a500;
            --sidebar-bg: #0f3d24;
            --sidebar-text: #c8e6d4;
            --sidebar-active: #ffffff;
            --body-bg:   #f4f6f9;
            --card-shadow: 0 2px 12px rgba(0,0,0,.08);
        }

        * { font-family: 'Vazirmatn', sans-serif; }

        body { background: var(--body-bg); }

        /* ─── سایدبار ───────────────────────────────────── */
        #sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            right: 0;
            top: 0;
            z-index: 1000;
            transition: transform .3s ease;
        }

        #sidebar .brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        #sidebar .brand h5 {
            color: #fff;
            font-weight: 700;
            margin: 0;
        }

        #sidebar .brand small {
            color: var(--sidebar-text);
            font-size: .75rem;
        }

        #sidebar .nav-label {
            color: rgba(200,230,212,.45);
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .9rem 1.25rem .3rem;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: .6rem 1.25rem;
            border-radius: 8px;
            margin: 2px 8px;
            font-size: .875rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            transition: all .2s;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: rgba(255,255,255,.12);
            color: var(--sidebar-active);
        }

        #sidebar .nav-link i { width: 18px; text-align: center; font-size: .9rem; }

        /* ─── محتوای اصلی ───────────────────────────────── */
        #main-content {
            margin-right: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ─── نوار بالا ─────────────────────────────────── */
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e8ecf0;
            padding: .75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 6px rgba(0,0,0,.06);
        }

        /* ─── کارت‌های آمار ─────────────────────────────── */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            box-shadow: var(--card-shadow);
            border-right: 4px solid var(--primary);
            transition: transform .2s;
        }

        .stat-card:hover { transform: translateY(-2px); }

        .stat-card .icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
        }

        .stat-card .value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a2332;
        }

        .stat-card .label {
            font-size: .8rem;
            color: #6b7a8d;
        }

        /* ─── کارت عمومی ────────────────────────────────── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #eef0f4;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        /* ─── جدول ──────────────────────────────────────── */
        .table th {
            background: #f8fafc;
            font-size: .78rem;
            font-weight: 700;
            color: #5a6880;
            text-transform: uppercase;
            border: none;
        }

        .table td { vertical-align: middle; font-size: .875rem; }

        /* ─── Badge وضعیت ───────────────────────────────── */
        .status-badge {
            padding: .3rem .7rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
        }

        /* ─── دکمه‌ها ───────────────────────────────────── */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: #155c33; border-color: #155c33; }

        /* ─── Alert ها ──────────────────────────────────── */
        .alert { border-radius: 10px; border: none; }

        /* ─── صفحه‌بندی ─────────────────────────────────── */
        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            font-size: .85rem;
        }

        /* ─── واکنش‌گرا ─────────────────────────────────── */
        @media (max-width: 991px) {
            #sidebar { transform: translateX(260px); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-right: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ─── سایدبار ───────────────────────────── --}}
<div id="sidebar">
    <div class="brand">
        <h5><i class="fas fa-pills me-2"></i> پخش دارو</h5>
        <small>سیستم مدیریت توزیع</small>
    </div>

    <nav class="mt-2">
        <div class="nav-label">دستیار</div>
        <a href="{{ route('admin.openclaw') }}"
           class="nav-link {{ request()->routeIs('admin.openclaw') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> دستیار سریع
        </a>
        <div class="nav-label">داشبورد</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> داشبورد
        </a>

        <div class="nav-label">انبار و کالا</div>
        <a href="{{ route('admin.medicines.index') }}"
           class="nav-link {{ request()->routeIs('admin.medicines.*') ? 'active' : '' }}">
            <i class="fas fa-capsules"></i> داروها
        </a>
        <a href="{{ route('admin.categories.index') }}"
           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> دسته‌بندی‌ها
        </a>

        <div class="nav-label">فروش</div>
        <a href="{{ route('admin.orders.index') }}"
           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i> سفارش‌ها
            @php $pending = \App\Models\Order::where('status','pending')->count() @endphp
            @if($pending)
                <span class="badge bg-warning text-dark ms-auto">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ route('admin.pharmacies.index') }}"
           class="nav-link {{ request()->routeIs('admin.pharmacies.*') ? 'active' : '' }}">
            <i class="fas fa-hospital"></i> داروخانه‌ها
        </a>
        <a href="{{ route('admin.invoices.index') }}"
           class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
            <i class="fas fa-file-invoice"></i> فاکتورها
        </a>

        <div class="nav-label">گزارش‌ها</div>
        <a href="{{ route('admin.reports.sales') }}"
           class="nav-link {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> گزارش فروش
        </a>
        <a href="{{ route('admin.reports.inventory') }}"
           class="nav-link {{ request()->routeIs('admin.reports.inventory') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i> گزارش موجودی
        </a>
        <a href="{{ route('admin.reports.low-stock') }}"
           class="nav-link {{ request()->routeIs('admin.reports.low-stock') ? 'active' : '' }}">
            <i class="fas fa-exclamation-triangle text-warning"></i> کمبود موجودی
        </a>

        @can('manage users')
            <div class="nav-label">مدیریت</div>
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> کاربران
            </a>
        @endcan
    </nav>

    {{-- اطلاعات کاربر در پایین سایدبار --}}
    <div class="position-absolute bottom-0 w-100 p-3 border-top" style="border-color:rgba(255,255,255,.1)!important">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white fw-bold"
                 style="width:36px;height:36px;font-size:.8rem">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <div class="text-white" style="font-size:.8rem;font-weight:600">{{ auth()->user()->name }}</div>
                <div style="color:var(--sidebar-text);font-size:.7rem">{{ auth()->user()->getRoleNames()->first() }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                @csrf
                <button class="btn btn-sm" style="color:var(--sidebar-text)" title="خروج">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ─── محتوای اصلی ─────────────────────────── --}}
<div id="main-content">

    {{-- نوار بالا --}}
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size:.82rem">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">خانه</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center gap-3">
            {{-- اعلان کمبود موجودی --}}
            @php $lowStock = \App\Models\Medicine::lowStock()->active()->count() @endphp
            @if($lowStock)
                <a href="{{ route('admin.reports.low-stock') }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    {{ $lowStock }} دارو کم‌موجود
                </a>
            @endif
            <span style="font-size:.8rem;color:#6b7a8d">{{ verta()->format('l d F Y') }}</span>
        </div>
    </div>

    {{-- محتوای صفحه --}}
    <div class="p-4 flex-grow-1">

        {{-- پیام‌های flash --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>خطاهای اعتبارسنجی:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // سایدبار موبایل
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            bootstrap.Alert.getOrCreateInstance(el).close();
        });
    }, 5000);
</script>

@stack('scripts')
</body>
</html>
