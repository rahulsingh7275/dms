<?php

namespace App\Models;

use App\Models\Deed;
use App\Models\IndexVerification;
use App\Models\QcVerification;
use App\Models\VaultRegistrationOffice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Index extends Model
{
    use HasFactory;

    protected $table = 'indexes';

    protected $fillable = [
        'state_id',
        'district_id',
        'vault_registration_office_id',
        'volume_year',
        'book_number',
        'volume_number',
        'is_volume_forwarded',
        'status',
        'locked',
        'created_by',
    ];

    protected $casts = [
        'is_volume_forwarded' => 'boolean',
        'locked' => 'boolean',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(VaultRegistrationOffice::class, 'vault_registration_office_id');
    }

    public function deeds(): HasMany
    {
        return $this->hasMany(Deed::class);
    }

    public function indexVerifications(): HasMany
    {
        return $this->hasMany(IndexVerification::class);
    }

    public function qcVerifications(): HasMany
    {
        return $this->hasMany(QcVerification::class);
    }
}
