<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\MassPrunable;

class Generation extends Model
{
    use SoftDeletes, MassPrunable;

    protected $fillable = [
        'user_id', 'source', 'domain_id',
        'person_image_path', 'garment_image_path', 'garment_url',
        'garment_type', 'wiro_job_id', 'result_image_path',
        'result_image_url', 'has_watermark', 'status',
        'credits_used', 'error_message', 'expires_at',
        'progress', 'started_at', 'completed_at', 'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'has_watermark' => 'boolean',
            'expires_at'    => 'datetime',
            'processed_at'  => 'datetime',
            'started_at'    => 'datetime',
            'completed_at'  => 'datetime',
        ];
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prunable()
    {
        return static::where('expires_at', '<', now());
    }

    protected function pruning(): void
    {
        \Storage::delete([
            $this->person_image_path,
            $this->garment_image_path,
            $this->result_image_path,
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'queued'     => 'Kuyrukta',
            'processing' => 'İşleniyor',
            'completed'  => 'Tamamlandı',
            'failed'     => 'Hata',
            default      => $this->status,
        };
    }
}