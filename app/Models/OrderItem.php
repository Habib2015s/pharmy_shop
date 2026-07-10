<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ============================================================
// فایل: app/Models/OrderItem.php
// ============================================================
class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'medicine_id', 'quantity',
        'unit_price', 'discount', 'subtotal'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // محاسبه مجدد subtotal
    public function recalculate(): void
    {
        $this->subtotal = ($this->unit_price * $this->quantity) - $this->discount;
        $this->save();
    }
}

