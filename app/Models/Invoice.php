<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ============================================================
// فایل: app/Models/Invoice.php
// ============================================================
class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'order_id', 'pharmacy_id',
        'amount', 'status', 'due_date', 'notes'
    ];

    protected $casts = ['due_date' => 'date'];

    public static function generateNumber(): string
    {
        $prefix = 'INV-' . date('Ym') . '-';
        $last   = self::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('id')->first();
        $next   = $last ? ((int) substr($last->invoice_number, -4) + 1) : 1;
        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function paidAmount(): float
    {
        return $this->payments->sum('amount');
    }

    public function remainingAmount(): float
    {
        return $this->amount - $this->paidAmount();
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'paid';
    }
}

