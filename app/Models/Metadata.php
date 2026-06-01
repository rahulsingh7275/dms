<?php

namespace App\Models;

use App\Models\Deed;
use App\Models\Instrument;
use App\Models\InstrumentType;
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
        'party_type',
        'relation_name',
        'district_id',
        'vault_registration_office_id',
        'circle',
        'property_details',
        'village',
        'khata_no',
        'khasra_no',
        'area',
        'registration_date',
        'presection_date',
        'instrument_type_id',
        'instrument_sub_type_id',
        'page_no_from',
        'page_no_to',
        'created_by',
        'status',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'presection_date' => 'date',
    ];

    public function deed(): BelongsTo
    {
        return $this->belongsTo(Deed::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class, 'instrument_type_id');
    }

    public function instrumentSubtype(): BelongsTo
    {
        return $this->belongsTo(InstrumentType::class, 'instrument_sub_type_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function registrationOffice(): BelongsTo
    {
        return $this->belongsTo(VaultRegistrationOffice::class, 'vault_registration_office_id');
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(MetadataVerification::class);
    }
}
