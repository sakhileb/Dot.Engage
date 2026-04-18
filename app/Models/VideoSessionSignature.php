<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoSessionSignature extends Model
{
    protected $fillable = [
        'video_session_id',
        'contract_id',
        'user_id',
        'signature_image_path',
        'ip_address',
        'signed_at',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
        ];
    }

    public function videoSession(): BelongsTo
    {
        return $this->belongsTo(VideoSession::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
