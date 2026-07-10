<?php

// ============================================================
// فایل: database/seeders/OrderSeeder.php
// ============================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Order, OrderItem, Pharmacy, Medicine, User, Invoice, Payment, StockMovement};
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $pharmacies = Pharmacy::where('is_active', true)->get();
        $medicines  = Medicine::where('is_active', true)->where('stock', '>', 20)->get();
        $admin      = User::role('admin')->first();
        $dist       = User::role('distributor')->first();

        if ($pharmacies->isEmpty() || $medicines->isEmpty()) {
            $this->command->warn('داروخانه یا دارو کافی برای ساخت سفارش وجود ندارد.');
            return;
        }

        $statuses  = ['pending','confirmed','processing','dispatched','delivered','delivered','delivered']; // بیشتر delivered
        $payMethods= ['cash','transfer','credit','cheque'];

        $orderCount = 0;

        // ─── ساخت ۴۰ سفارش در ۶ ماه گذشته ────────────────
        for ($i = 0; $i < 40; $i++) {
            $pharmacy  = $pharmacies->random();
            $status    = $statuses[array_rand($statuses)];
            $createdAt = Carbon::now()->subDays(rand(1, 180));

            // انتخاب ۲ تا ۵ دارو تصادفی
            $selectedMeds = $medicines->random(rand(2, 5));

            $total    = 0;
            $discount = rand(0, 1) ? rand(5000, 50000) : 0;
            $items    = [];

            foreach ($selectedMeds as $med) {
                $qty      = rand(5, 50);
                $price    = $med->sale_price;
                $subtotal = $price * $qty;
                $total   += $subtotal;

                $items[] = [
                    'medicine_id' => $med->id,
                    'quantity'    => $qty,
                    'unit_price'  => $price,
                    'discount'    => 0,
                    'subtotal'    => $subtotal,
                ];
            }

            $tax   = ($total - $discount) * 0.09;
            $final = $total - $discount + $tax;

            // شماره سفارش یکتا
            static $orderIndex = 1;
            $prefix      = 'ORD-' . $createdAt->format('Ymd') . '-';
            $orderNumber = $prefix . str_pad($orderIndex++, 4, '0', STR_PAD_LEFT);

            $timestamps = ['created_at' => $createdAt, 'updated_at' => $createdAt];

            if ($status === 'confirmed' || $status === 'processing' || $status === 'dispatched' || $status === 'delivered') {
                $timestamps['confirmed_at'] = $createdAt->copy()->addHours(rand(1,6));
            }
            if ($status === 'dispatched' || $status === 'delivered') {
                $timestamps['dispatched_at'] = $createdAt->copy()->addHours(rand(8,24));
            }
            if ($status === 'delivered') {
                $timestamps['delivered_at'] = $createdAt->copy()->addDays(rand(1,3));
            }

            $order = Order::create(array_merge([
                'order_number'   => $orderNumber,
                'pharmacy_id'    => $pharmacy->id,
                'user_id'        => rand(0,1) ? $admin->id : $dist->id,
                'status'         => $status,
                'total_amount'   => $total,
                'discount'       => $discount,
                'tax'            => $tax,
                'final_amount'   => $final,
                'payment_method' => $payMethods[array_rand($payMethods)],
                'payment_status' => $status === 'delivered' ? (rand(0,1) ? 'paid' : 'unpaid') : 'unpaid',
                'notes'          => rand(0,1) ? 'سفارش عادی' : null,
            ], $timestamps));

            // ─── اقلام سفارش ─────────────────────────────────
            foreach ($items as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            // ─── فاکتور و پرداخت برای سفارش‌های تحویلی ──────
            if ($status === 'delivered') {
                $invoice = Invoice::create([
                    'invoice_number' => 'INV-' . $createdAt->format('Ym') . '-' . str_pad($orderIndex, 4, '0', STR_PAD_LEFT),
                    'order_id'       => $order->id,
                    'pharmacy_id'    => $pharmacy->id,
                    'amount'         => $final,
                    'status'         => $order->payment_status === 'paid' ? 'paid' : 'issued',
                    'due_date'       => $createdAt->copy()->addDays(30),
                    'created_at'     => $timestamps['delivered_at'] ?? $createdAt,
                    'updated_at'     => $timestamps['delivered_at'] ?? $createdAt,
                ]);

                // ثبت حرکت انبار
                foreach ($items as $item) {
                    $med = Medicine::find($item['medicine_id']);
                    if ($med) {
                        StockMovement::create([
                            'medicine_id'  => $med->id,
                            'user_id'      => $admin->id,
                            'type'         => 'out',
                            'quantity'     => $item['quantity'],
                            'stock_before' => $med->stock + $item['quantity'],
                            'stock_after'  => $med->stock,
                            'order_id'     => $order->id,
                            'note'         => 'تحویل سفارش ' . $order->order_number,
                            'created_at'   => $timestamps['delivered_at'] ?? $createdAt,
                            'updated_at'   => $timestamps['delivered_at'] ?? $createdAt,
                        ]);
                    }
                }

                // ثبت پرداخت اگر paid بود
                if ($order->payment_status === 'paid') {
                    Payment::create([
                        'invoice_id'   => $invoice->id,
                        'pharmacy_id'  => $pharmacy->id,
                        'user_id'      => $admin->id,
                        'amount'       => $final,
                        'method'       => $payMethods[array_rand($payMethods)],
                        'reference'    => 'REF-' . rand(100000, 999999),
                        'payment_date' => ($timestamps['delivered_at'] ?? $createdAt)->copy()->addDays(rand(1,5)),
                        'note'         => 'پرداخت فاکتور سفارش',
                    ]);
                }
            }

            $orderCount++;
        }

        $this->command->info("✅ {$orderCount} سفارش با فاکتور و پرداخت وارد شد.");
    }
}
