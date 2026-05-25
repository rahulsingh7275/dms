<?php

namespace App\Models;

use App\Models\Index;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndexVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'index_id',
        'checker_id',
        'status',
        'remarks',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function index(): BelongsTo
    {
        return $this->belongsTo(Index::class);
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checker_id');
    }
}
