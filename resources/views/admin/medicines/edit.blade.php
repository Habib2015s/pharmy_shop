{{-- ============================================================
     فایل: resources/views/admin/medicines/edit.blade.php
     توضیح: فرم ویرایش دارو با مقادیر از دیتابیس
     ============================================================ --}}
@extends('admin.layout.app')
@section('title', 'ویرایش دارو')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.medicines.index') }}">داروها</a></li>
    <li class="breadcrumb-item active">ویرایش: {{ $medicine->name }}</li>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">ویرایش دارو</h4>
        <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i> بازگشت
        </a>
    </div>

    <form action="{{ route('admin.medicines.update', $medicine) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">

                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-info-circle me-2"></i>اطلاعات پایه</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600">نام تجاری <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $medicine->name) }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">نام ژنریک <span class="text-danger">*</span></label>
                                <input type="text" name="generic_name"
                                       class="form-control @error('generic_name') is-invalid @enderror"
                                       value="{{ old('generic_name', $medicine->generic_name) }}">
                                @error('generic_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">دسته‌بندی <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id', $medicine->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">بارکد</label>
                                <input type="text" name="barcode" class="form-control"
                                       value="{{ old('barcode', $medicine->barcode) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">سازنده</label>
                                <input type="text" name="manufacturer" class="form-control"
                                       value="{{ old('manufacturer', $medicine->manufacturer) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">واحد <span class="text-danger">*</span></label>
                                <select name="unit" class="form-select">
                                    @foreach(['عدد','بسته','ویال','آمپول','شیشه','کارتن'] as $u)
                                        <option value="{{ $u }}"
                                            {{ old('unit', $medicine->unit) === $u ? 'selected' : '' }}>
                                            {{ $u }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">تاریخ انقضا</label>
                                <input type="date" name="expiry_date" class="form-control"
                                       value="{{ old('expiry_date', $medicine->expiry_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-tag me-2"></i>قیمت‌گذاری</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-600">قیمت خرید (تومان)</label>
                                <input type="number" name="purchase_price" id="purchasePrice"
                                       class="form-control" step="100" min="0"
                                       value="{{ old('purchase_price', $medicine->purchase_price) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-600">قیمت فروش (تومان)</label>
                                <input type="number" name="sale_price" id="salePrice"
                                       class="form-control" step="100" min="0"
                                       value="{{ old('sale_price', $medicine->sale_price) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-600">حاشیه سود</label>
                                <div class="input-group">
                                    <input type="text" id="profitMargin" class="form-control" readonly
                                           style="background:#f8f9fa">
                                    <span class="input-group-text">٪</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- موجودی — فقط min_stock قابل ویرایش؛ stock از طریق تنظیم موجودی تغییر می‌کند --}}
                <div class="card">
                    <div class="card-header"><i class="fas fa-boxes me-2"></i>موجودی انبار</div>
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label fw-600">موجودی فعلی</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly
                                           value="{{ number_format($medicine->stock) }} {{ $medicine->unit }}"
                                           style="background:#f8f9fa">
                                </div>
                                <div class="form-text">برای تغییر موجودی از دکمه «تنظیم موجودی» استفاده کنید.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">حداقل موجودی (هشدار)</label>
                                <input type="number" name="min_stock" class="form-control"
                                       value="{{ old('min_stock', $medicine->min_stock) }}" min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- تصویر --}}
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-image me-2"></i>تصویر دارو</div>
                    <div class="card-body text-center">
                        <div class="border rounded p-2 mb-3 d-flex align-items-center justify-content-center"
                             style="height:150px;background:#f8f9fa">
                            @if($medicine->image)
                                <img id="previewImg" src="{{ asset('storage/'.$medicine->image) }}"
                                     style="max-height:130px;max-width:100%;border-radius:8px">
                            @else
                                <div id="previewPlaceholder">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                                <img id="previewImg" src="#" style="max-height:130px;display:none;border-radius:8px">
                            @endif
                        </div>
                        <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                        <div class="form-text">آپلود تصویر جدید جایگزین می‌شود.</div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-cog me-2"></i>تنظیمات</div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="requires_prescription"
                                   value="1" id="prescSwitch"
                                {{ old('requires_prescription', $medicine->requires_prescription) ? 'checked' : '' }}>
                            <label class="form-check-label" for="prescSwitch">نیاز به نسخه پزشک</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   value="1" id="activeSwitch"
                                {{ old('is_active', $medicine->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activeSwitch">دارو فعال است</label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i> ذخیره تغییرات
                    </button>
                    <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-secondary">
                        انصراف
                    </a>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = () => {
                const img = document.getElementById('previewImg');
                img.src = reader.result;
                img.style.display = 'block';
                const ph = document.getElementById('previewPlaceholder');
                if (ph) ph.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });

        function calcMargin() {
            const buy  = parseFloat(document.getElementById('purchasePrice').value) || 0;
            const sell = parseFloat(document.getElementById('salePrice').value) || 0;
            const el   = document.getElementById('profitMargin');
            const pct  = buy > 0 ? (((sell - buy) / buy) * 100).toFixed(1) : '—';
            el.value = pct;
            el.style.color = parseFloat(pct) < 0 ? 'red' : '#1a6b3c';
        }
        document.getElementById('purchasePrice').addEventListener('input', calcMargin);
        document.getElementById('salePrice').addEventListener('input', calcMargin);
        calcMargin(); // اجرا هنگام بارگذاری
    </script>
@endpush
