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
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3">Vault Office Details</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-muted mb-1">District</label>
                            <div class="fw-semibold">{{ optional($index?->district)->name ?? '-' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted mb-1">Office</label>
                            <div class="fw-semibold">{{ optional($index?->office)->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3">Volume Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">District</label>
                            <div class="fw-semibold">{{ optional($index?->district)->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Office</label>
                            <div class="fw-semibold">{{ optional($index?->office)->name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Year</label>
                            <div class="fw-semibold">{{ $index?->volume_year ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Volume Forwarded</label>
                            <div class="fw-semibold">{{ $index?->is_volume_forwarded ? 'Yes' : 'No' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted mb-1">Reverted Remark by DSR</label>
                            <div class="border rounded p-3 bg-light text-muted">{{ $latestIndexVerification?->remarks ?? 'No reverted remark available.' }}</div>
                        </div>
                        <div class="col-12">
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

                                <ul class="nav nav-tabs mb-3" id="metadataTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="index-tab" data-bs-toggle="tab" data-bs-target="#indexTab" type="button" role="tab" aria-controls="indexTab" aria-selected="true">Index</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="party-tab" data-bs-toggle="tab" data-bs-target="#partyTab" type="button" role="tab" aria-controls="partyTab" aria-selected="false">Party Details</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="property-tab" data-bs-toggle="tab" data-bs-target="#propertyTab" type="button" role="tab" aria-controls="propertyTab" aria-selected="false">Property Details</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="metadataTabsContent">
                                    <div class="tab-pane fade show active" id="indexTab" role="tabpanel" aria-labelledby="index-tab">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Presentation Year</label>
                                                <input type="text" name="presentation_year" class="form-control" value="{{ old('presentation_year') }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Deed Number</label>
                                                <input type="text" name="deed_number" class="form-control" value="{{ old('deed_number') }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Presection Date</label>
                                                <input type="date" name="presection_date" class="form-control" value="{{ old('presection_date') }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Registration Date</label>
                                                <input type="date" name="registration_date" class="form-control" value="{{ old('registration_date') }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Instrument Type</label>
                                                <select name="instrument_type_id" class="form-select" required>
                                                    <option value="">Select Instrument Type</option>
                                                    @foreach($instruments as $instrument)
                                                        <option value="{{ $instrument->id }}" {{ old('instrument_type_id') == $instrument->id ? 'selected' : '' }}>{{ $instrument->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Instrument Sub Type</label>
                                                <select name="instrument_sub_type_id" class="form-select" required>
                                                    <option value="">Select Instrument Sub Type</option>
                                                    @foreach($instrumentTypes as $instrumentType)
                                                        <option value="{{ $instrumentType->id }}" {{ old('instrument_sub_type_id') == $instrumentType->id ? 'selected' : '' }}>{{ $instrumentType->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Page No. From</label>
                                                <input type="number" name="page_no_from" class="form-control" min="1" value="{{ old('page_no_from') }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Page No. To</label>
                                                <input type="number" name="page_no_to" class="form-control" min="1" value="{{ old('page_no_to') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="partyTab" role="tabpanel" aria-labelledby="party-tab">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Party Name</label>
                                                <input type="text" name="party_name" class="form-control" value="{{ old('party_name') }}">
                                            </div>
                                            <div class="col-12">
                                                <div class="border rounded p-4 bg-light">
                                                    <p class="mb-2 fw-semibold">Dummy Party Details</p>
                                                    <p class="mb-1 text-muted">Party Name: Demo Party</p>
                                                    <p class="mb-1 text-muted">Guardian / Representative: Demo Representative</p>
                                                    <p class="mb-0 text-muted">Contact: demo@example.com</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="propertyTab" role="tabpanel" aria-labelledby="property-tab">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Property Details</label>
                                                <textarea name="property_details" class="form-control" rows="4">{{ old('property_details') }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Village</label>
                                                <input type="text" name="village" class="form-control" value="{{ old('village') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Area</label>
                                                <input type="text" name="area" class="form-control" value="{{ old('area') }}">
                                            </div>
                                            <div class="col-12">
                                                <div class="border rounded p-4 bg-light">
                                                    <p class="mb-2 fw-semibold">Dummy Property Details</p>
                                                    <p class="mb-1 text-muted">Village: Demo Village</p>
                                                    <p class="mb-1 text-muted">Area: 100 sq ft</p>
                                                    <p class="mb-0 text-muted">Property Type: Residential</p>
                                                </div>
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
