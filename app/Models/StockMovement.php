<?php

// ============================================================
// فایل: app/Models/StockMovement.php
// ============================================================
use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id', 'user_id', 'type', 'quantity',
        'stock_before', 'stock_after', 'order_id', 'reference', 'note'
    ];

    const TYPE_LABELS = [
        'in'         => 'ورود به انبار',
        'out'        => 'خروج از انبار',
        'adjustment' => 'تعدیل موجودی',
        'return'     => 'مرجوعی',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function typeLabel(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }
}

