<?php

namespace App\Models;

use App\Models\Index;
use App\Models\Metadata;
use App\Models\ScannedDocument;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deed extends Model
{
    use HasFactory;

    protected $fillable = [
        'index_id',
        'presentation_year',
        'deed_number',
        'party_name',
        'property_details',
        'village',
        'area',
        'registration_date',
        'status',
    ];

    protected $casts = [
        'registration_date' => 'date',
    ];

    public function index(): BelongsTo
    {
        return $this->belongsTo(Index::class);
    }

    public function scannedDocuments(): HasMany
    {
        return $this->hasMany(ScannedDocument::class);
    }

    public function metadata() {
        return $this->hasOne(Metadata::class);
    }
}
