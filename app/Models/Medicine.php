<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ============================================================
// فایل: app/Models/Medicine.php
// ============================================================
class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'generic_name', 'barcode', 'unit',
        'purchase_price', 'sale_price', 'stock', 'min_stock',
        'expiry_date', 'manufacturer', 'image',
        'requires_prescription', 'is_active'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'requires_prescription' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ─── روابط ───────────────────────────────────────────────
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // ─── متدهای کمکی ──────────────────────────────────────────
    // آیا موجودی کم است؟
    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    // آیا منقضی شده؟
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    // درصد سود
    public function profitMargin(): float
    {
        if ($this->purchase_price == 0) return 0;
        return round((($this->sale_price - $this->purchase_price) / $this->purchase_price) * 100, 2);
    }

    // ─── Scope ها ─────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>=', now());
    }
}
