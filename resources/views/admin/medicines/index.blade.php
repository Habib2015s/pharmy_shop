{{-- ============================================================
     فایل: resources/views/admin/medicines/index.blade.php
     توضیح: لیست داروها با فیلتر، جستجو و نمایش وضعیت موجودی
     ============================================================ --}}
@extends('admin.layout.app')

@section('title', 'مدیریت داروها')

@section('breadcrumb')
    <li class="breadcrumb-item active">داروها</li>
@endsection

@section('content')

    {{-- ─── هدر صفحه ──────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">داروها</h4>
            <small class="text-muted">مدیریت موجودی و قیمت‌گذاری</small>
        </div>
        <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> ثبت دارو جدید
        </a>
    </div>

    {{-- ─── فیلترها ───────────────────────────────────────────── --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.medicines.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-600">جستجو</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="نام دارو، نام ژنریک، بارکد..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-600">دسته‌بندی</label>
                    <select name="category_id" class="form-select">
                        <option value="">همه دسته‌ها</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-600">فیلتر وضعیت</label>
                    <select name="filter" class="form-select">
                        <option value="">همه</option>
                        <option value="low_stock" {{ request('filter') === 'low_stock' ? 'selected' : '' }}>
                            کمبود موجودی
                        </option>
                        <option value="expiring" {{ request('filter') === 'expiring' ? 'selected' : '' }}>
                            رو به انقضا (۳۰ روز)
                        </option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100">اعمال</button>
                    <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── جدول داروها ───────────────────────────────────────── --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-capsules me-2"></i>لیست داروها
            <span class="badge bg-secondary ms-1">{{ $medicines->total() }}</span>
        </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>تصویر</th>
                        <th>نام دارو</th>
                        <th>دسته</th>
                        <th>موجودی</th>
                        <th>قیمت خرید</th>
                        <th>قیمت فروش</th>
                        <th>انقضا</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($medicines as $med)
                        <tr class="{{ $med->isLowStock() ? 'table-warning' : '' }}">
                            <td class="text-muted" style="font-size:.78rem">{{ $med->id }}</td>
                            <td>
                                @if($med->image)
                                    <img src="{{ asset('storage/'.$med->image) }}" width="40" height="40"
                                         class="rounded" style="object-fit:cover">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                         style="width:40px;height:40px;color:#aaa">
                                        <i class="fas fa-pills"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-600">{{ $med->name }}</div>
                                <small class="text-muted">{{ $med->generic_name }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $med->category->name }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                <span class="fw-700 {{ $med->isLowStock() ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($med->stock) }}
                                </span>
                                    <span class="text-muted" style="font-size:.75rem">{{ $med->unit }}</span>
                                    @if($med->isLowStock())
                                        <i class="fas fa-exclamation-triangle text-danger" title="کمبود موجودی"></i>
                                    @endif
                                </div>
                                {{-- Progress bar موجودی --}}
                                @php $pct = min(100, ($med->min_stock > 0 ? ($med->stock / ($med->min_stock * 3)) * 100 : 100)) @endphp
                                <div class="progress mt-1" style="height:3px">
                                    <div class="progress-bar {{ $med->isLowStock() ? 'bg-danger' : 'bg-success' }}"
                                         style="width:{{ $pct }}%"></div>
                                </div>
                            </td>
                            <td>{{ number_format($med->purchase_price) }} <small class="text-muted">ت</small></td>
                            <td>{{ number_format($med->sale_price) }} <small class="text-muted">ت</small></td>
                            <td>
                                @if($med->expiry_date)
                                    <span class="{{ $med->isExpired() ? 'text-danger fw-bold' : 'text-muted' }}"
                                          style="font-size:.8rem">
                                    {{ verta($med->expiry_date)->format('Y/m/d') }}
                                        @if($med->isExpired()) <br><small class="text-danger">منقضی!</small> @endif
                                </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($med->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-secondary">غیرفعال</span>
                                @endif
                                @if($med->requires_prescription)
                                    <span class="badge bg-info mt-1 d-block">نیاز به نسخه</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.medicines.edit', $med) }}"
                                       class="btn btn-sm btn-outline-primary" title="ویرایش">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{-- دکمه تنظیم موجودی --}}
                                    <button class="btn btn-sm btn-outline-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#adjustModal"
                                            data-id="{{ $med->id }}"
                                            data-name="{{ $med->name }}"
                                            data-stock="{{ $med->stock }}"
                                            title="تنظیم موجودی">
                                        <i class="fas fa-boxes"></i>
                                    </button>
                                    {{-- حذف --}}
                                    <form action="{{ route('admin.medicines.destroy', $med) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('آیا از حذف این دارو مطمئن هستید؟')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-pills fa-3x mb-3 d-block opacity-25"></i>
                                هیچ داروی یافت نشد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">
                نمایش {{ $medicines->firstItem() }} تا {{ $medicines->lastItem() }}
                از {{ $medicines->total() }} رکورد
            </small>
            {{ $medicines->links() }}
        </div>
    </div>

    {{-- ─── مودال تنظیم موجودی ────────────────────────────────── --}}
    <div class="modal fade" id="adjustModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تنظیم موجودی — <span id="adjMedName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="adjustForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info py-2">
                            موجودی فعلی: <strong id="adjCurrentStock"></strong>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نوع عملیات</label>
                            <select name="type" class="form-select" required>
                                <option value="in">ورود به انبار</option>
                                <option value="out">خروج از انبار</option>
                                <option value="adjustment">تعدیل (موجودی جدید)</option>
                                <option value="return">مرجوعی</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تعداد</label>
                            <input type="number" name="quantity" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">توضیح <span class="text-danger">*</span></label>
                            <input type="text" name="note" class="form-control" required
                                   placeholder="دلیل تغییر موجودی را بنویسید...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-primary">ثبت تغییر</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // مودال تنظیم موجودی: پر کردن داده‌های دارو
        document.getElementById('adjustModal').addEventListener('show.bs.modal', e => {
            const btn = e.relatedTarget;
            document.getElementById('adjMedName').textContent     = btn.dataset.name;
            document.getElementById('adjCurrentStock').textContent = btn.dataset.stock;
            document.getElementById('adjustForm').action =
                `/admin/medicines/${btn.dataset.id}/adjust-stock`;
        });
    </script>
@endpush
