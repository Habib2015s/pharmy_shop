<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Medicine, Category, Pharmacy, Order, OrderItem, StockMovement, Invoice, Payment};

// ============================================================
// فایل: app/Http/Controllers/Admin/InvoiceController.php
// توضیح: مشاهده فاکتور و دانلود PDF
// ============================================================
class InvoiceController extends Controller
{
    public function show(Invoice $invoice)
    {
        $invoice->load(['order.items.medicine', 'pharmacy', 'payments']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load(['order.items.medicine', 'pharmacy']);
        $pdf = \PDF::loadView('admin.invoices.pdf', compact('invoice'))
            ->setPaper('A4');
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    // ثبت پرداخت
    public function registerPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount'       => 'required|numeric|min:1|max:' . $invoice->remainingAmount(),
            'method'       => 'required|in:cash,transfer,cheque,pos',
            'reference'    => 'nullable|string',
            'payment_date' => 'required|date',
            'note'         => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            Payment::create([
                'invoice_id'   => $invoice->id,
                'pharmacy_id'  => $invoice->pharmacy_id,
                'user_id'      => auth()->id(),
                'amount'       => $request->amount,
//                'method'       => $request->method,
                'reference'    => $request->reference,
                'payment_date' => $request->payment_date,
                'note'         => $request->note,
            ]);

            // کاهش بدهی داروخانه
            $invoice->pharmacy->decrement('current_balance', $request->amount);

            // اگر کاملاً پرداخت شد
            if ($invoice->remainingAmount() <= 0) {
                $invoice->update(['status' => 'paid']);
                $invoice->order->update(['payment_status' => 'paid']);
            } else {
                $invoice->order->update(['payment_status' => 'partial']);
            }
        });

        return back()->with('success', 'پرداخت ثبت شد.');
    }
}

