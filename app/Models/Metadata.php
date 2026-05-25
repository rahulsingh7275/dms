<?php

namespace App\Models;

use App\Models\Deed;
use App\Models\MetadataVerification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Metadata extends Model
{
    use HasFactory;

    protected $table = 'metadata';

    protected $fillable = [
        'deed_id',
        'presentation_year',
        'deed_number',
        'party_name',
        'property_details',
        'village',
        'area',
        'registration_date',
        'created_by',
        'status',
    ];

    protected $casts = [
        'registration_date' => 'date',
    ];

    public function deed(): BelongsTo
    {
        return $this->belongsTo(Deed::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(MetadataVerification::class);
    }
}
