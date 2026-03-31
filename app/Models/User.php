<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'avatar',
        'credit_balance', 'is_active', 'is_admin',
        'referral_code', 'referred_by',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'is_admin'          => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (User $user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(Str::random(8));
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin && $this->is_active;
    }

    public function transactions() { return $this->hasMany(Transaction::class); }
    public function generations()  { return $this->hasMany(Generation::class); }
    public function domains()      { return $this->hasMany(Domain::class); }
    public function referrer()     { return $this->belongsTo(User::class, 'referred_by'); }
    public function referrals()    { return $this->hasMany(User::class, 'referred_by'); }

    public function addCredits(int $amount, string $type = 'gift', ?string $note = null): void
    {
        $this->increment('credit_balance', $amount);
        $this->transactions()->create([
            'order_id'      => 'GIFT-' . strtoupper(Str::random(10)),
            'amount'        => 0,
            'credit_amount' => $amount,
            'status'        => 'paid',
            'type'          => $type,
            'note'          => $note,
            'paid_at'       => now(),
        ]);
    }

  public function deductCredits(int $amount, string $type = 'usage', string $note = ''): bool
{
    if ($this->credit_balance < $amount) return false;

    $this->decrement('credit_balance', $amount);
    
    \App\Models\Transaction::create([
        'user_id'       => $this->id,
        'package_id'    => null,
        'order_id'      => 'USG-' . strtoupper(\Illuminate\Support\Str::random(10)),
        'amount'        => 0,
        'currency'      => 'TRY',
        'credit_amount' => $amount,
        'status'        => 'paid',
        'type'          => $type,
        'note'          => $note,
        'paid_at'       => now(),
    ]);

    return true;
}

    public function hasEnoughCredits(int $amount): bool
    {
        return $this->credit_balance >= $amount;
    }

    public function user()
{
    return $this->belongsTo(User::class);
}
}