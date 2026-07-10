
{{-- ============================================================
     فایل: resources/views/shop/products/index.blade.php
     توضیح: لیست همه داروها با فیلتر دسته + جستجو
     ============================================================ --}}
@extends('layout.app')
@section('title', 'داروها')

@section('content')
    <div class="container py-4">
        <div class="row g-4">

            {{-- ─── سایدبار فیلتر ─────────────────────────── --}}
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm" style="border-radius:14px;position:sticky;top:80px">
                    <div class="card-header bg-success text-white" style="border-radius:14px 14px 0 0">
                        <i class="fas fa-filter me-2"></i>فیلتر داروها
                    </div>
                    <div class="card-body p-3">
                        <form method="GET" id="filterForm">
                            {{-- جستجو --}}
                            <div class="mb-3">
                                <label class="form-label fw-700" style="font-size:.82rem">جستجو</label>
                                <input type="text" name="q" class="form-control form-control-sm"
                                       placeholder="نام دارو..." value="{{ request('q') }}">
                            </div>

                            {{-- دسته‌بندی --}}
                            <div class="mb-3">
                                <label class="form-label fw-700" style="font-size:.82rem">دسته‌بندی</label>
                                @foreach($categories as $cat)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category"
                                               value="{{ $cat->slug }}" id="cat_{{ $cat->id }}"
                                               {{ request('category') === $cat->slug ? 'checked' : '' }}
                                               onchange="document.getElementById('filterForm').submit()">
                                        <label class="form-check-label" for="cat_{{ $cat->id }}"
                                               style="font-size:.85rem">
                                            {{ $cat->name }}
                                            <span class="text-muted">({{ $cat->activeMedicinesCount() }})</span>
                                        </label>
                                    </div>
                                @endforeach
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="radio" name="category"
                                           value="" id="cat_all"
                                           {{ !request('category') ? 'checked' : '' }}
                                           onchange="document.getElementById('filterForm').submit()">
                                    <label class="form-check-label" for="cat_all"
                                           style="font-size:.85rem;color:var(--green);font-weight:600">همه دسته‌ها</label>
                                </div>
                            </div>

                            {{-- محدوده قیمت --}}
                            <div class="mb-3">
                                <label class="form-label fw-700" style="font-size:.82rem">محدوده قیمت (تومان)</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" name="price_min" class="form-control form-control-sm"
                                               placeholder="از" value="{{ request('price_min') }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="price_max" class="form-control form-control-sm"
                                               placeholder="تا" value="{{ request('price_max') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- فقط موجود --}}
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="in_stock"
                                           value="1" id="inStockSwitch"
                                           {{ request('in_stock') ? 'checked' : '' }}
                                           onchange="document.getElementById('filterForm').submit()">
                                    <label class="form-check-label" for="inStockSwitch" style="font-size:.85rem">
                                        فقط موجود
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 btn-sm">
                                <i class="fas fa-filter me-1"></i> اعمال فیلتر
                            </button>
                            <a href="{{ route('shop.products') }}" class="btn btn-outline-secondary w-100 btn-sm mt-2">
                                پاک کردن
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ─── لیست داروها ───────────────────────────── --}}
            <div class="col-lg-9">

                {{-- toolbar --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="fw-700">{{ $medicines->total() }} دارو</span>
                        @if(request('category'))
                            <span class="badge bg-success ms-1">{{ request('category') }}</span>
                        @endif
                        @if(request('q'))
                            <span class="badge bg-info ms-1">جستجو: {{ request('q') }}</span>
                        @endif
                    </div>
                    <select name="sort" class="form-select form-select-sm w-auto"
                            onchange="window.location='?{{ http_build_query(array_merge(request()->all(), ['sort'=>''])) }}&sort='+this.value">
                        <option value="default">مرتب‌سازی</option>
                        <option value="price_asc"  {{ request('sort')==='price_asc'  ? 'selected':'' }}>
                            ارزان‌ترین
                        </option>
                        <option value="price_desc" {{ request('sort')==='price_desc' ? 'selected':'' }}>
                            گران‌ترین
                        </option>
                        <option value="newest"     {{ request('sort')==='newest'     ? 'selected':'' }}>
                            جدیدترین
                        </option>
                    </select>
                </div>

                {{-- گرید محصولات --}}
                <div class="row g-3">
                    @forelse($medicines as $med)
                        <div class="col-6 col-md-4 col-xl-3">
                            @include('shop.partials.medicine-card', ['med' => $med])
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5 text-muted">
                                <div style="font-size:4rem">🔍</div>
                                <h5>دارویی یافت نشد</h5>
                                <p>فیلترها را تغییر دهید یا همه داروها را ببینید</p>
                                <a href="{{ route('shop.products') }}" class="btn btn-outline-success">
                                    نمایش همه
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- صفحه‌بندی --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $medicines->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


