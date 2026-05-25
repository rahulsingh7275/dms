<?php

namespace App\Models;

use App\Models\Metadata;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetadataVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'metadata_id',
        'checker_id',
        'status',
        'remarks',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function metadata(): BelongsTo
    {
        return $this->belongsTo(Metadata::class);
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checker_id');
    }
}
