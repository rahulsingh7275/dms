@extends('layouts.app')

@section('title', 'Add Metadata')

@section('content')
@php
    $index = $deed->index;
    $latestScannedDocument = $deed->scannedDocuments->last();
    $latestIndexVerification = $index?->indexVerifications->sortByDesc('verified_at')->first();
    $latestQcVerification = $index?->qcVerifications->sortByDesc('verified_at')->first();
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1">Metadata Review</p>
            <h3 class="mb-0">Add Metadata for Deed #{{ $deed->id }}</h3>
        </div>
        <span class="badge bg-light text-dark border">Deed #{{ $deed->id }}</span>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3">Volume Details</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-muted mb-1">District</label>
                            <div class="fw-semibold">{{ optional($index?->district)->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted mb-1">Office</label>
                            <div class="fw-semibold">{{ optional($index?->office)->office_name ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted mb-1">Year</label>
                            <div class="fw-semibold">{{ $index?->volume_year ?? '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted mb-1">Volume Forwarded</label>
                            <div class="fw-semibold">{{ $index?->is_volume_forwarded ? 'Yes' : 'No' }}</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label text-muted mb-1">Reverted Remark by DSR</label>
                            <div class="border rounded p-3 bg-light text-muted">{{ $latestIndexVerification?->remarks ?? 'No reverted remark available.' }}</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label text-muted mb-1">Reverted Remark by Checker</label>
                            <div class="border rounded p-3 bg-light text-muted">{{ $latestQcVerification?->remarks ?? 'No reverted remark available.' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row g-4 align-items-start">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body p-4">
                            <h5 class="mb-3">Metadata Form</h5>
                            <form method="POST" action="{{ route('deeds.metadata.store', $deed) }}">
                                @csrf

                                @php
                                    $prefPresentationYear = old('presentation_year', $deed->presentation_year ?? $index?->volume_year ?? '');
                                    $prefDeedNumber = old('deed_number', $deed->deed_number ?? '');
                                    $prefPresectionDate = old('presection_date', optional($deed->presection_date)->format('Y-m-d') ?? '');
                                    $prefRegistrationDate = old('registration_date', optional($deed->registration_date)->format('Y-m-d') ?? '');
                                    $prefInstrumentType = old('instrument_type_id', $deed->instrument_type_id ?? '');
                                    $prefInstrumentSubType = old('instrument_sub_type_id', $deed->instrument_sub_type_id ?? '');
                                    $prefPageFrom = old('page_no_from', $deed->page_no_from ?? '');
                                    $prefPageTo = old('page_no_to', $deed->page_no_to ?? '');
                                    $prefPartyName = old('party_name', $deed->party_name ?? '');
                                    $prefRelationName = old('relation_name', $deed->relation_name ?? '');
                                    $prefDistrict = old('district_id', $deed->district_id ?? $index?->district_id ?? '');
                                    $prefOffice = old('vault_registration_office_id', $deed->vault_registration_office_id ?? $index?->vault_registration_office_id ?? '');
                                    $prefCircle = old('circle', $deed->circle ?? '');
                                    $prefVillage = old('village', $deed->village ?? '');
                                    $prefKhata = old('khata_no', $deed->khata_no ?? '');
                                    $prefKhasra = old('khasra_no', $deed->khasra_no ?? '');
                                @endphp

                                <ul class="nav nav-tabs mb-3" id="metadataTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="index-tab" data-toggle="tab" data-target="#indexTab" type="button" role="tab" aria-controls="indexTab" aria-selected="true">Index</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="party-tab" data-toggle="tab" data-target="#partyTab" type="button" role="tab" aria-controls="partyTab" aria-selected="false">Party Details</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="property-tab" data-toggle="tab" data-target="#propertyTab" type="button" role="tab" aria-controls="propertyTab" aria-selected="false">Property Details</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="metadataTabsContent">
                                    <div class="tab-pane fade show active" id="indexTab" role="tabpanel" aria-labelledby="index-tab">
                                        <div class="row g-3">
                                            
                                            <div class="col-md-6">
                                                <input type="hidden" name="presentation_year" value="{{ $prefPresentationYear }}">
                                                <input type="hidden" name="deed_number" value="{{ $prefDeedNumber }}">
                                                <label class="form-label">Presentation Date</label>
                                                <input type="date" name="presection_date" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $prefPresectionDate }}" required {{ $prefPresectionDate !== '' ? 'readonly' : '' }}
                                                max="{{ now()->format('Y-m-d') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Registration Date</label>
                                                <input type="date" name="registration_date" style="border: 1px solid #c0c0c0;" class="form-control" required max="{{ now()->format('Y-m-d') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Instrument Type</label>
                                                @if($prefInstrumentType !== '')
                                                    <select name="instrument_type_id_disabled" class="form-control" style="border: 1px solid #c0c0c0;" disabled>
                                                        <option value="">Select Instrument Type</option>
                                                        @foreach($instruments ?? [] as $instrument)
                                                            <option value="{{ $instrument->id }}" {{ $prefInstrumentType == $instrument->id ? 'selected' : '' }}>{{ $instrument->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="instrument_type_id" value="{{ $prefInstrumentType }}">
                                                @else
                                                    <select name="instrument_type_id" class="form-control" style="border: 1px solid #c0c0c0;" required>
                                                        <option value="">Select Instrument Type</option>
                                                        @foreach($instruments ?? [] as $instrument)
                                                            <option value="{{ $instrument->id }}" {{ old('instrument_type_id') == $instrument->id ? 'selected' : '' }}>{{ $instrument->name }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Instrument Sub Type</label>
                                                @if($prefInstrumentSubType !== '')
                                                    <select name="instrument_sub_type_id_disabled" class="form-control" style="border: 1px solid #c0c0c0;" disabled>
                                                        <option value="">Select Instrument Sub Type</option>
                                                        @foreach($instrumentTypes ?? [] as $instrumentType)
                                                            <option value="{{ $instrumentType->id }}" {{ $prefInstrumentSubType == $instrumentType->id ? 'selected' : '' }}>{{ $instrumentType->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="instrument_sub_type_id" value="{{ $prefInstrumentSubType }}">
                                                @else
                                                    <select name="instrument_sub_type_id" class="form-control" style="border: 1px solid #c0c0c0;" required>
                                                        <option value="">Select Instrument Sub Type</option>
                                                        @foreach($instrumentTypes ?? [] as $instrumentType)
                                                            <option value="{{ $instrumentType->id }}" {{ old('instrument_sub_type_id') == $instrumentType->id ? 'selected' : '' }}>{{ $instrumentType->name }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Page No. From</label>
                                                <input type="number" name="page_no_from" style="border: 1px solid #c0c0c0;" class="form-control" min="1" value="{{ $prefPageFrom }}" required {{ $prefPageFrom !== '' ? 'readonly' : '' }}>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Page No. To</label>
                                                <input type="number" name="page_no_to" style="border: 1px solid #c0c0c0;" class="form-control" min="1" value="{{ $prefPageTo }}" required {{ $prefPageTo !== '' ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="partyTab" role="tabpanel" aria-labelledby="party-tab">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Party Type</label>
                                                <select name="party_type" class="form-control" style="border: 1px solid #c0c0c0;">
                                                    <option value="">Select Party Type</option>
                                                    <option value="executant" {{ old('party_type') === 'executant' ? 'selected' : '' }}>Executant</option>
                                                    <option value="claimant" {{ old('party_type') === 'claimant' ? 'selected' : '' }}>Claimant</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="party_name" style="border: 1px solid #c0c0c0;" class="form-control" >
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Relation Name</label>
                                                <input type="text" name="relation_name" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $prefRelationName }}" {{ $prefRelationName !== '' ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="propertyTab" role="tabpanel" aria-labelledby="property-tab">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">District</label>
                                                @if($prefDistrict !== '')
                                                    @php($selectedDistrict = collect($districts ?? [])->firstWhere('id', $prefDistrict))
                                                    @if($selectedDistrict)
                                                        <select name="district_id_disabled" class="form-control" style="border: 1px solid #c0c0c0;" disabled>
                                                            <option value="">Select District</option>
                                                                @foreach($districts ?? [] as $district)
                                                                    <option value="{{ $district->id }}" {{ $prefDistrict == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                                                @endforeach
                                                        </select>
                                                        <input type="hidden" name="district_id" value="{{ $prefDistrict }}">
                                                    @else
                                                        <input type="text" class="form-control" value="{{ optional($index?->district)->name ?? '-' }}" readonly style="border: 1px solid #c0c0c0;">
                                                        <input type="hidden" name="district_id" value="{{ $prefDistrict }}">
                                                    @endif
                                                @else
                                                    <select name="district_id" class="form-control" style="border: 1px solid #c0c0c0;">
                                                        <option value="">Select District</option>
                                                            @foreach($districts ?? [] as $district)
                                                                <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                                            @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Registration Office</label>
                                                @if($prefOffice !== '')
                                                    @php($selectedOffice = collect($offices ?? [])->firstWhere('id', $prefOffice))
                                                    @if($selectedOffice)
                                                        <select name="vault_registration_office_id_disabled" class="form-control" style="border: 1px solid #c0c0c0;" disabled>
                                                            <option value="">Select Registration Office</option>
                                                            @foreach($offices ?? [] as $office)
                                                                <option value="{{ $office->id }}" {{ $prefOffice == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="vault_registration_office_id" value="{{ $prefOffice }}">
                                                    @else
                                                        <input type="text" class="form-control" value="{{ optional($index?->office)->office_name ?? '-' }}" readonly style="border: 1px solid #c0c0c0;">
                                                        <input type="hidden" name="vault_registration_office_id" value="{{ $prefOffice }}">
                                                    @endif
                                                @else
                                                    <select name="vault_registration_office_id" class="form-control" style="border: 1px solid #c0c0c0;">
                                                        <option value="">Select Registration Office</option>
                                                        @foreach($offices ?? [] as $office)
                                                            <option value="{{ $office->id }}" {{ old('vault_registration_office_id') == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Circle</label>
                                                <input type="text" name="circle" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $prefCircle }}" {{ $prefCircle !== '' ? 'readonly' : '' }}>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Village / Thana</label>
                                                <input type="text" name="village" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $prefVillage }}" {{ $prefVillage !== '' ? 'readonly' : '' }}>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Khata No.</label>
                                                <input type="text" name="khata_no" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $prefKhata }}" {{ $prefKhata !== '' ? 'readonly' : '' }}>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Khasra No.</label>
                                                <input type="text" name="khasra_no" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $prefKhasra }}" {{ $prefKhasra !== '' ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-primary mt-4">Save Metadata</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="border rounded p-3 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Uploaded Deed PDF</h5>
                            @if($latestScannedDocument)
                                <a href="{{ asset('storage/' . $latestScannedDocument->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Open PDF</a>
                            @endif
                        </div>

                        @if($latestScannedDocument)
                            <iframe
                                src="{{ asset('storage/' . $latestScannedDocument->file_path) }}"
                                class="w-100 rounded"
                                style="min-height: 70vh; border: 1px solid #dee2e6;"
                                title="Uploaded deed PDF"
                            ></iframe>
                        @else
                            <div class="border rounded p-5 text-center bg-light text-muted">
                                No uploaded deed PDF is available yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    (function($){
        $(function(){
            $('#metadataTabs .nav-link').on('click', function(e){
                e.preventDefault();
                var $link = $(this);
                var target = $link.data('bs-target') || $link.attr('href') || $link.data('target');
                if (!target) return;

                // Deactivate all links and panes
                $('#metadataTabs .nav-link').removeClass('active').attr('aria-selected','false');
                $('#metadataTabsContent .tab-pane').removeClass('show active');

                // Activate clicked link and corresponding pane
                $link.addClass('active').attr('aria-selected','true');
                $(target).addClass('show active');
            });
        });
    })(jQuery);
</script>
@endsection
