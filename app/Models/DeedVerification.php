<?php

namespace App\Models;

use App\Models\Deed;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeedVerification extends Model
{
    use HasFactory;

    protected $table = 'deed_verifications';

    protected $fillable = [
        'deed_id',
        'checker_id',
        'status',
        'remarks',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function deed(): BelongsTo
    {
        return $this->belongsTo(Deed::class);
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checker_id');
    }
}
