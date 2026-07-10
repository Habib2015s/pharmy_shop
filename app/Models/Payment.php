<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ============================================================
// فایل: app/Models/Payment.php
// ============================================================
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'pharmacy_id', 'user_id',
        'amount', 'method', 'reference', 'payment_date', 'note'
    ];

    protected $casts = ['payment_date' => 'date'];

    const METHOD_LABELS = [
        'cash'     => 'نقدی',
        'transfer' => 'انتقال بانکی',
        'cheque'   => 'چک',
        'pos'      => 'کارتخوان',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function methodLabel(): string
    {
        return self::METHOD_LABELS[$this->method] ?? $this->method;
    }
}
