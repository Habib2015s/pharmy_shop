{{-- ============================================================
     فایل: resources/views/admin/medicines/create.blade.php
     توضیح: فرم ثبت دارو جدید
     ============================================================ --}}
@extends('admin.layout.app')
@section('title', 'ثبت دارو جدید')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.medicines.index') }}">داروها</a></li>
    <li class="breadcrumb-item active">ثبت جدید</li>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">ثبت دارو جدید</h4>
        <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i> بازگشت
        </a>
    </div>

    <form action="{{ route('admin.medicines.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            {{-- ─── ستون چپ: اطلاعات اصلی ─────────────────── --}}
            <div class="col-lg-8">

                {{-- اطلاعات پایه --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i>اطلاعات پایه
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600">نام تجاری <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="مثال: آموکسی‌سیلین ۵۰۰">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">نام ژنریک <span class="text-danger">*</span></label>
                                <input type="text" name="generic_name"
                                       class="form-control @error('generic_name') is-invalid @enderror"
                                       value="{{ old('generic_name') }}" placeholder="Amoxicillin">
                                @error('generic_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">دسته‌بندی <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">انتخاب کنید...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">بارکد</label>
                                <input type="text" name="barcode"
                                       class="form-control @error('barcode') is-invalid @enderror"
                                       value="{{ old('barcode') }}" placeholder="اختیاری">
                                @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">سازنده</label>
                                <input type="text" name="manufacturer" class="form-control"
                                       value="{{ old('manufacturer') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">واحد <span class="text-danger">*</span></label>
                                <select name="unit" class="form-select @error('unit') is-invalid @enderror">
                                    <option value="">انتخاب...</option>
                                    <option value="عدد" {{ old('unit')=='عدد'?'selected':'' }}>عدد</option>
                                    <option value="بسته" {{ old('unit')=='بسته'?'selected':'' }}>بسته</option>
                                    <option value="ویال" {{ old('unit')=='ویال'?'selected':'' }}>ویال</option>
                                    <option value="آمپول" {{ old('unit')=='آمپول'?'selected':'' }}>آمپول</option>
                                    <option value="شیشه" {{ old('unit')=='شیشه'?'selected':'' }}>شیشه</option>
                                    <option value="کارتن" {{ old('unit')=='کارتن'?'selected':'' }}>کارتن</option>
                                </select>
                                @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">تاریخ انقضا</label>
                                <input type="date" name="expiry_date" class="form-control"
                                       value="{{ old('expiry_date') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- قیمت‌گذاری --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-tag me-2"></i>قیمت‌گذاری
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-600">قیمت خرید (تومان) <span class="text-danger">*</span></label>
                                <input type="number" name="purchase_price"
                                       class="form-control @error('purchase_price') is-invalid @enderror"
                                       value="{{ old('purchase_price') }}" min="0" step="100"
                                       id="purchasePrice">
                                @error('purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-600">قیمت فروش (تومان) <span class="text-danger">*</span></label>
                                <input type="number" name="sale_price"
                                       class="form-control @error('sale_price') is-invalid @enderror"
                                       value="{{ old('sale_price') }}" min="0" step="100"
                                       id="salePrice">
                                @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-600">حاشیه سود</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="profitMargin" readonly
                                           style="background:#f8f9fa">
                                    <span class="input-group-text">٪</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- موجودی --}}
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-boxes me-2"></i>موجودی انبار
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600">موجودی اولیه <span class="text-danger">*</span></label>
                                <input type="number" name="stock"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       value="{{ old('stock', 0) }}" min="0">
                                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">حداقل موجودی (هشدار)</label>
                                <input type="number" name="min_stock" class="form-control"
                                       value="{{ old('min_stock', 10) }}" min="0">
                                <div class="form-text">وقتی موجودی به این عدد رسید هشدار داده می‌شود.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── ستون راست: تصویر + تنظیمات ───────────── --}}
            <div class="col-lg-4">
                {{-- تصویر --}}
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-image me-2"></i>تصویر دارو</div>
                    <div class="card-body text-center">
                        <div class="border rounded p-3 mb-3 d-flex align-items-center justify-content-center"
                             style="height:160px;background:#f8f9fa">
                            <img id="previewImg" src="#" alt="پیش‌نمایش"
                                 style="max-height:140px;max-width:100%;display:none;border-radius:8px">
                            <div id="previewPlaceholder">
                                <i class="fas fa-image fa-3x text-muted"></i>
                                <p class="text-muted mt-2 mb-0" style="font-size:.8rem">تصویر انتخاب نشده</p>
                            </div>
                        </div>
                        <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                        <div class="form-text">حداکثر ۲ مگابایت — JPG، PNG</div>
                    </div>
                </div>

                {{-- تنظیمات --}}
                <div class="card">
                    <div class="card-header"><i class="fas fa-cog me-2"></i>تنظیمات</div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="requires_prescription"
                                   id="prescSwitch" value="1"
                                {{ old('requires_prescription') ? 'checked' : '' }}>
                            <label class="form-check-label" for="prescSwitch">
                                نیاز به نسخه پزشک
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="activeSwitch" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activeSwitch">
                                دارو فعال است
                            </label>
                        </div>
                    </div>
                </div>

                {{-- دکمه ذخیره --}}
                <div class="d-grid mt-4 gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i> ذخیره دارو
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
        // پیش‌نمایش تصویر
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = () => {
                document.getElementById('previewImg').src = reader.result;
                document.getElementById('previewImg').style.display = 'block';
                document.getElementById('previewPlaceholder').style.display = 'none';
            };
            reader.readAsDataURL(file);
        });

        // محاسبه حاشیه سود
        function calcMargin() {
            const buy  = parseFloat(document.getElementById('purchasePrice').value) || 0;
            const sell = parseFloat(document.getElementById('salePrice').value) || 0;
            const pct  = buy > 0 ? (((sell - buy) / buy) * 100).toFixed(1) : '—';
            document.getElementById('profitMargin').value = pct;
            document.getElementById('profitMargin').style.color = pct < 0 ? 'red' : '#1a6b3c';
        }

        document.getElementById('purchasePrice').addEventListener('input', calcMargin);
        document.getElementById('salePrice').addEventListener('input', calcMargin);
    </script>
@endpush


{{-- ============================================================
     فایل: resources/views/admin/medicines/edit.blade.php
     توضیح: فرم ویرایش دارو (همانند create، با مقادیر پیش‌فرض)
     ============================================================ --}}
{{--
   این فایل دقیقاً مثل create.blade.php است با دو تفاوت:
   ۱) عنوان و route تغییر می‌کند
   ۲) مقادیر old() با $medicine->field جایگزین می‌شود

   برای جلوگیری از تکرار کد، می‌توانید از @include استفاده کنید
   یا مستقیم همین فایل را کپی و مقادیر زیر را جایگزین کنید:
--}}
