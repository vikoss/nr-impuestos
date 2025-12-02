<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'user_id',
        'uuid',
        'original_string',
        'digital_seal',
        'pdf_hash',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
