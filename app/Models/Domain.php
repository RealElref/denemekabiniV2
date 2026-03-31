<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'domain_name', 'tld', 'registration_years',
        'credits_used', 'price_paid', 'status', 'admin_note',
        'registered_at', 'expires_at',
        'api_key', 'daily_limit', 'total_requests', 'last_request_at',
    ];

    protected function casts(): array
    {
        return [
            'registered_at'   => 'datetime',
            'expires_at'      => 'datetime',
            'last_request_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullDomainAttribute(): string
    {
        return $this->domain_name . $this->tld;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Onay Bekliyor',
            'approved'  => 'Onaylandı',
            'active'    => 'Aktif',
            'rejected'  => 'Reddedildi',
            'expired'   => 'Süresi Doldu',
            'cancelled' => 'İptal',
            default     => $this->status,
        };
    }
}