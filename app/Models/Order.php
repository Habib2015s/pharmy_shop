<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ============================================================
// فایل: app/Models/Order.php
// ============================================================
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'pharmacy_id', 'user_id', 'status',
        'total_amount', 'discount', 'tax', 'final_amount',
        'payment_status', 'payment_method', 'notes',
        'confirmed_at', 'dispatched_at', 'delivered_at'
    ];

    protected $casts = [
        'confirmed_at'  => 'datetime',
        'dispatched_at' => 'datetime',
        'delivered_at'  => 'datetime',
    ];

    // رنگ badge وضعیت برای UI
    const STATUS_COLORS = [
        'pending'    => 'warning',
        'confirmed'  => 'info',
        'processing' => 'primary',
        'dispatched' => 'secondary',
        'delivered'  => 'success',
        'cancelled'  => 'danger',
    ];

    const STATUS_LABELS = [
        'pending'    => 'در انتظار بررسی',
        'confirmed'  => 'تأیید شده',
        'processing' => 'در حال آماده‌سازی',
        'dispatched' => 'ارسال شده',
        'delivered'  => 'تحویل داده شده',
        'cancelled'  => 'لغو شده',
    ];

    // ─── روابط ───────────────────────────────────────────────
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // ─── متدهای کمکی ──────────────────────────────────────────
    // تولید شماره سفارش یکتا
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-' . date('Ymd') . '-';
        $last   = self::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('id')->first();
        $next   = $last ? ((int) substr($last->order_number, -4) + 1) : 1;
        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    // برچسب وضعیت فارسی
    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    // رنگ وضعیت
    public function statusColor(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    // آیا قابل لغو است؟
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    // محاسبه مجدد مبالغ
    public function recalculate(): void
    {
        $total = $this->items->sum('subtotal');
        $tax   = $total * 0.09; // ۹ درصد مالیات
        $final = $total - $this->discount + $tax;

        $this->update([
            'total_amount' => $total,
            'tax'          => $tax,
            'final_amount' => $final,
        ]);
    }

    // ─── Scope ها ─────────────────────────────────────────────
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }
}


