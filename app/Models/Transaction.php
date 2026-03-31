<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'order_id', 'pos_transaction_id',
        'pos_payment_url', 'amount', 'currency', 'credit_amount',
        'status', 'type', 'note', 'pos_payload', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'pos_payload' => 'array',
            'paid_at'     => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getAmountFormattedAttribute(): string
    {
        return number_format($this->amount / 100, 2) . ' ₺';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Bekliyor',
            'paid'      => 'Ödendi',
            'failed'    => 'Başarısız',
            'refunded'  => 'İade Edildi',
            'cancelled' => 'İptal',
            default     => $this->status,
        };
    }

    protected static function boot(): void
{
    parent::boot();
    static::creating(function (Transaction $transaction) {
        if (empty($transaction->order_id)) {
            $transaction->order_id = 'TXN-' . strtoupper(\Illuminate\Support\Str::random(10));
        }
    });
}
}