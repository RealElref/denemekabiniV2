<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'credit_amount', 'price',
        'currency', 'badge_label', 'badge_color',
        'features', 'is_active', 'is_featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'features'    => 'array',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getPriceFormattedAttribute(): string
    {
        return number_format($this->price / 100, 2) . ' ₺';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getTranslatedNameAttribute(): string
{
    $key = 'pkg_' . $this->slug . '_name';
    $translated = __($key);
    return $translated === $key ? $this->name : $translated;
}

public function getTranslatedDescAttribute(): string
{
    $key = 'pkg_' . $this->slug . '_desc';
    $translated = __($key);
    return $translated === $key ? $this->description : $translated;
}

public function getTranslatedFeaturesAttribute(): array
{
    if (!$this->features) return [];
    
    $features = $this->features;
    $translated = [];
    
    foreach ($features as $index => $feature) {
        $text = is_array($feature) ? $feature['item'] : $feature;
        $key = 'pkg_' . $this->slug . '_feat_' . $index;
        $trans = __($key);
        $translated[] = $trans === $key ? $text : $trans;
    }
    
    return $translated;
}

public function getTranslatedBadgeAttribute(): ?string
{
    if (!$this->badge_label) return null;
    $key = 'badge_' . Str::slug($this->badge_label, '_');
    $trans = __($key);
    return $trans === $key ? $this->badge_label : $trans;
}
}