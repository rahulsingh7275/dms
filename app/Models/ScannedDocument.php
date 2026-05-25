<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScannedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'deed_id',
        'file_name',
        'file_path',
        'file_type',
        'uploaded_by',
    ];

    public function deed(): BelongsTo
    {
        return $this->belongsTo(Deed::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
