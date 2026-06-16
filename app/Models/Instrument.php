<?php

namespace App\Models;

use App\Models\InstrumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    public function instrumentTypes(): HasMany
    {
        return $this->hasMany(InstrumentType::class);
    }
}
