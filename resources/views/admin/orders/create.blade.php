
{{-- ============================================================
     فایل: resources/views/admin/orders/create.blade.php
     توضیح: فرم ثبت سفارش جدید با انتخاب دارو داینامیک
     ============================================================ --}}
@extends('admin.layout.app')
@section('title', 'ثبت سفارش')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">سفارش‌ها</a></li>
    <li class="breadcrumb-item active">ثبت سفارش</li>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">ثبت سفارش جدید</h4>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i> بازگشت
        </a>
    </div>

    <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
        @csrf

        <div class="row g-4">

            {{-- ─── ستون اصلی: اقلام سفارش ──────────────── --}}
            <div class="col-lg-8">

                {{-- انتخاب داروخانه --}}
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-hospital me-2"></i>داروخانه</div>
                    <div class="card-body">
                        <select name="pharmacy_id" class="form-select form-select-lg
                            @error('pharmacy_id') is-invalid @enderror" required>
                            <option value="">انتخاب داروخانه...</option>
                            @foreach($pharmacies as $ph)
                                <option value="{{ $ph->id }}"
                                        data-credit="{{ $ph->availableCredit() }}"
                                    {{ old('pharmacy_id') == $ph->id ? 'selected' : '' }}>
                                    {{ $ph->name }} — {{ $ph->city }}
                                    (اعتبار: {{ number_format($ph->availableCredit()) }} ت)
                                </option>
                            @endforeach
                        </select>
                        @error('pharmacy_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- جدول اقلام --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-list me-2"></i>اقلام سفارش</span>
                        <button type="button" class="btn btn-sm btn-success" id="addRowBtn">
                            <i class="fas fa-plus me-1"></i> افزودن ردیف
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle" id="itemsTable">
                                <thead>
                                <tr>
                                    <th style="width:35%">دارو</th>
                                    <th>موجودی</th>
                                    <th>قیمت واحد</th>
                                    <th style="width:100px">تعداد</th>
                                    <th>تخفیف</th>
                                    <th>جمع</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="itemsBody">
                                {{-- ردیف اول خودکار --}}
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][medicine_id]" class="form-select medicine-select" required>
                                            <option value="">انتخاب دارو...</option>
                                            @foreach($medicines as $med)
                                                <option value="{{ $med->id }}"
                                                        data-price="{{ $med->sale_price }}"
                                                        data-stock="{{ $med->stock }}"
                                                        data-unit="{{ $med->unit }}">
                                                    {{ $med->name }} ({{ $med->generic_name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="stock-cell text-muted">—</td>
                                    <td class="price-cell text-muted">—</td>
                                    <td>
                                        <input type="number" name="items[0][quantity]"
                                               class="form-control qty-input" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][discount]"
                                               class="form-control disc-input" min="0" value="0">
                                    </td>
                                    <td class="subtotal-cell fw-700 text-success">—</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── ستون راست: خلاصه سفارش ─────────────── --}}
            <div class="col-lg-4">
                <div class="card mb-4 position-sticky" style="top:80px">
                    <div class="card-header"><i class="fas fa-receipt me-2"></i>خلاصه سفارش</div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">جمع اقلام:</span>
                            <span id="totalItems" class="fw-600">۰</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تخفیف کلی (تومان)</label>
                            <input type="number" name="discount" id="orderDiscount"
                                   class="form-control" min="0" value="0">
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">مالیات (۹٪):</span>
                            <span id="taxAmount">۰</span>
                        </div>
                        <div class="d-flex justify-content-between fw-700 fs-5 text-success">
                            <span>مبلغ نهایی:</span>
                            <span id="finalAmount">۰</span>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-600">روش پرداخت</label>
                            <select name="payment_method" class="form-select">
                                <option value="">انتخاب...</option>
                                <option value="cash">نقدی</option>
                                <option value="transfer">انتقال بانکی</option>
                                <option value="credit">اعتباری</option>
                                <option value="cheque">چک</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">توضیحات</label>
                            <textarea name="notes" class="form-control" rows="3"
                                      placeholder="توضیحات اختیاری...">{{ old('notes') }}</textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i> ثبت سفارش
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        // ─── داده داروها برای JS ─────────────────────────────────
        const medicines = @json(
    $medicines->keyBy('id')->map(function ($m) {
        return [
            'id'      => $m->id,
            'name'    => $m->name,
            'generic' => $m->generic_name,
            'price'   => $m->sale_price,
            'stock'   => $m->stock,
            'unit'    => $m->unit,
        ];
    })
);

        let rowIndex = 1;

        // ─── محاسبه subtotal هر ردیف ─────────────────────────────
        function calcRow(row) {
            const select = row.querySelector('.medicine-select');
            const qty    = parseFloat(row.querySelector('.qty-input').value) || 0;
            const disc   = parseFloat(row.querySelector('.disc-input').value) || 0;
            const medId  = select.value;

            if (medId && medicines[medId]) {
                const med   = medicines[medId];
                const price = med.price;
                const sub   = (price * qty) - disc;

                row.querySelector('.stock-cell').textContent = `${med.stock} ${med.unit}`;
                row.querySelector('.price-cell').textContent = price.toLocaleString('fa') + ' ت';
                row.querySelector('.subtotal-cell').textContent = sub.toLocaleString('fa') + ' ت';
            }
            calcTotal();
        }

        // ─── محاسبه جمع کل ──────────────────────────────────────
        function calcTotal() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const sub = row.querySelector('.subtotal-cell').textContent;
                total += parseFloat(sub.replace(/[^0-9.]/g, '')) || 0;
            });
            const disc  = parseFloat(document.getElementById('orderDiscount').value) || 0;
            const tax   = (total - disc) * 0.09;
            const final = total - disc + tax;

            document.getElementById('totalItems').textContent  = total.toLocaleString('fa') + ' ت';
            document.getElementById('taxAmount').textContent   = Math.round(tax).toLocaleString('fa') + ' ت';
            document.getElementById('finalAmount').textContent = Math.round(final).toLocaleString('fa') + ' ت';
        }

        // ─── افزودن ردیف جدید ───────────────────────────────────
        document.getElementById('addRowBtn').addEventListener('click', () => {
            const template = document.querySelector('.item-row').cloneNode(true);
            // reset
            template.querySelectorAll('select, input').forEach(el => el.value = '');
            template.querySelector('.qty-input').value  = 1;
            template.querySelector('.disc-input').value = 0;
            template.querySelector('.stock-cell').textContent   = '—';
            template.querySelector('.price-cell').textContent   = '—';
            template.querySelector('.subtotal-cell').textContent = '—';

            // rename
            template.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
            });
            rowIndex++;

            document.getElementById('itemsBody').appendChild(template);
            attachRowEvents(template);
        });

        // ─── event listeners ─────────────────────────────────────
        function attachRowEvents(row) {
            row.querySelector('.medicine-select').addEventListener('change', () => calcRow(row));
            row.querySelector('.qty-input').addEventListener('input', () => calcRow(row));
            row.querySelector('.disc-input').addEventListener('input', () => calcRow(row));
            row.querySelector('.remove-row').addEventListener('click', () => {
                if (document.querySelectorAll('.item-row').length > 1) {
                    row.remove(); calcTotal();
                }
            });
        }

        // attach به ردیف اول
        attachRowEvents(document.querySelector('.item-row'));
        document.getElementById('orderDiscount').addEventListener('input', calcTotal);
    </script>
@endpush


