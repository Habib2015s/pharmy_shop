<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
// ============================================================
// فایل: app/Models/Pharmacy.php
// ============================================================
class Pharmacy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'owner_name', 'license_number',
        'phone', 'mobile', 'email', 'province', 'city', 'address',
        'credit_limit', 'current_balance', 'is_active'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    // ─── روابط ───────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ─── متدهای کمکی ──────────────────────────────────────────
    // اعتبار باقیمانده
    public function availableCredit(): float
    {
        return $this->credit_limit - $this->current_balance;
    }

    // جمع سفارشات این ماه
    public function monthlyOrdersTotal(): float
    {
        return $this->orders()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'delivered')
            ->sum('final_amount');
    }
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}

