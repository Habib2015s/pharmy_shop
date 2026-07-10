{{-- ============================================================
     فایل: resources/views/admin/invoices/pdf.blade.php
     توضیح: قالب فاکتور برای خروجی PDF با DomPDF
     نصب:  composer require barryvdh/laravel-dompdf
     ============================================================ --}}
    <!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاکتور {{ $invoice->invoice_number }}</title>
    <style>
        @font-face {
            font-family: 'Vazirmatn';
            src: url('{{ storage_path("fonts/Vazirmatn-Regular.ttf") }}') format('truetype');
        }

        * {
            font-family: 'Vazirmatn', Arial, sans-serif;
            margin: 0; padding: 0; box-sizing: border-box;
        }

        body {
            font-size: 10pt;
            color: #1a2332;
            direction: rtl;
        }

        /* ─── سربرگ فاکتور ─── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px 0 15px;
            border-bottom: 3px solid #1a6b3c;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1a6b3c;
        }

        .company-sub {
            font-size: 9pt;
            color: #666;
            margin-top: 3px;
        }

        .invoice-title {
            font-size: 22pt;
            font-weight: bold;
            color: #1a2332;
            text-align: left;
        }

        .invoice-number {
            font-size: 10pt;
            color: #666;
            text-align: left;
        }

        /* ─── اطلاعات فاکتور ─── */
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .meta-table td {
            padding: 5px 8px;
            font-size: 9.5pt;
        }

        .meta-box {
            background: #f4f6f9;
            border-radius: 6px;
            padding: 12px;
            width: 48%;
            display: inline-block;
        }

        .meta-box .label {
            color: #666;
            font-size: 8.5pt;
        }

        .meta-box .value {
            font-weight: bold;
            font-size: 10pt;
            margin-top: 2px;
        }

        /* ─── جدول اقلام ─── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background: #1a6b3c;
            color: white;
            padding: 8px 10px;
            font-size: 9pt;
            text-align: right;
        }

        .items-table td {
            padding: 7px 10px;
            font-size: 9pt;
            border-bottom: 1px solid #e8ecf0;
        }

        .items-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        /* ─── جمع‌بندی ─── */
        .summary {
            width: 280px;
            margin-right: auto;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 9.5pt;
            border-bottom: 1px solid #eee;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 12pt;
            color: #1a6b3c;
            border-bottom: 2px solid #1a6b3c;
            padding: 8px 0;
        }

        /* ─── وضعیت پرداخت ─── */
        .payment-status {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 9pt;
            font-weight: bold;
        }

        .status-paid     { background: #e8f5ee; color: #1a6b3c; }
        .status-unpaid   { background: #fce4ec; color: #c62828; }
        .status-partial  { background: #fff8e1; color: #f57f17; }

        /* ─── پاورقی ─── */
        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #e8ecf0;
            font-size: 8.5pt;
            color: #888;
            text-align: center;
        }

        .stamp-area {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            font-size: 9pt;
        }

        .stamp-box {
            width: 30%;
            text-align: center;
            border-top: 1px dashed #ccc;
            padding-top: 8px;
            color: #666;
        }
    </style>
</head>
<body>

{{-- سربرگ --}}
<div class="header">
    <div>
        <div class="company-name">🏥 شرکت پخش دارو</div>
        <div class="company-sub">توزیع دارو و مستلزمات پزشکی</div>
        <div class="company-sub">تلفن: ۰۳۱-۱۲۳۴۵۶۷۸ | ایمیل: info@pharma.ir</div>
    </div>
    <div>
        <div class="invoice-title">فاکتور فروش</div>
        <div class="invoice-number">{{ $invoice->invoice_number }}</div>
    </div>
</div>

{{-- اطلاعات داروخانه و فاکتور --}}
<table class="meta-table">
    <tr>
        <td>
            <div class="meta-box">
                <div class="label">داروخانه:</div>
                <div class="value">{{ $invoice->pharmacy->name }}</div>
                <div style="font-size:8.5pt;color:#666;margin-top:3px">
                    مالک: {{ $invoice->pharmacy->owner_name }}<br>
                    پروانه: {{ $invoice->pharmacy->license_number }}<br>
                    {{ $invoice->pharmacy->city }}، {{ $invoice->pharmacy->address }}
                </div>
            </div>
        </td>
        <td style="text-align:left">
            <div class="meta-box" style="text-align:right">
                <div class="label">شماره فاکتور:</div>
                <div class="value">{{ $invoice->invoice_number }}</div>
                <div style="font-size:8.5pt;color:#666;margin-top:4px">
                    شماره سفارش: {{ $invoice->order->order_number }}<br>
                    تاریخ صدور: {{ verta($invoice->created_at)->format('Y/m/d') }}<br>
                    @if($invoice->due_date)
                        سررسید: {{ verta($invoice->due_date)->format('Y/m/d') }}
                    @endif
                </div>
            </div>
        </td>
    </tr>
</table>

{{-- جدول اقلام --}}
<table class="items-table">
    <thead>
    <tr>
        <th>#</th>
        <th>نام دارو</th>
        <th>نام ژنریک</th>
        <th>واحد</th>
        <th>تعداد</th>
        <th>قیمت واحد (ت)</th>
        <th>تخفیف (ت)</th>
        <th>جمع (ت)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->order->items as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->medicine->name }}</td>
            <td style="color:#666">{{ $item->medicine->generic_name }}</td>
            <td>{{ $item->medicine->unit }}</td>
            <td style="text-align:center">{{ number_format($item->quantity) }}</td>
            <td>{{ number_format($item->unit_price) }}</td>
            <td>{{ $item->discount > 0 ? number_format($item->discount) : '—' }}</td>
            <td style="font-weight:bold">{{ number_format($item->subtotal) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- جمع‌بندی --}}
<div class="summary">
    <div class="summary-row">
        <span>جمع اقلام:</span>
        <span>{{ number_format($invoice->order->total_amount) }} ت</span>
    </div>
    @if($invoice->order->discount > 0)
        <div class="summary-row">
            <span>تخفیف:</span>
            <span style="color:#c62828">— {{ number_format($invoice->order->discount) }} ت</span>
        </div>
    @endif
    <div class="summary-row">
        <span>مالیات (۹٪):</span>
        <span>{{ number_format($invoice->order->tax) }} ت</span>
    </div>
    <div class="summary-row total">
        <span>مبلغ نهایی:</span>
        <span>{{ number_format($invoice->order->final_amount) }} ت</span>
    </div>
</div>

{{-- وضعیت پرداخت --}}
<div style="margin-bottom:15px">
    وضعیت پرداخت:
    <span class="payment-status {{ $invoice->status === 'paid' ? 'status-paid' : ($invoice->paidAmount() > 0 ? 'status-partial' : 'status-unpaid') }}">
        @if($invoice->status === 'paid') پرداخت شده
        @elseif($invoice->paidAmount() > 0) نیمه‌پرداخت ({{ number_format($invoice->paidAmount()) }} ت)
        @else پرداخت نشده
        @endif
    </span>
</div>

{{-- محل امضا --}}
<div class="stamp-area">
    <div class="stamp-box">امضا و مهر فروشنده</div>
    <div class="stamp-box">امضا و مهر خریدار</div>
    <div class="stamp-box">تأیید مدیر مالی</div>
</div>

{{-- پاورقی --}}
<div class="footer">
    این فاکتور به صورت سیستمی صادر شده و معتبر می‌باشد |
    تاریخ چاپ: {{ verta()->format('Y/m/d H:i') }} |
    سیستم مدیریت پخش دارو
</div>

</body>
</html>

