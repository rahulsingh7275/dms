<?php

namespace App\Models;

use App\Models\Index;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VaultRegistrationOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'office_name',
        'office_code',
        'address',
        'status',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function indexes(): HasMany
    {
        return $this->hasMany(Index::class);
    }
}
