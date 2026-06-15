@extends('layouts.app')

@section('title', 'Edit Metadata')

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
            <h3 class="mb-0">Edit Metadata for Deed #{{ $deed->id }}</h3>
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
                            <div class="fw-semibold">{{ optional($index?->office)->name ?? '-' }}</div>
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
                            <form method="POST" action="{{ route('deeds.metadata.update', [$deed, $metadata]) }}">
                                @csrf
                                @method('PUT')

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
                                                <input type="text" name="presentation_year" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ old('presentation_year', $metadata->presentation_year) }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Deed Number</label>
                                                <input type="text" name="deed_number" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ old('deed_number', $metadata->deed_number) }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Presection Date</label>
                                                <input type="date" name="presection_date" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ old('presection_date', optional($metadata->presection_date)->format('Y-m-d')) }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Registration Date</label>
                                                <input type="date" name="registration_date" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ old('registration_date', optional($metadata->registration_date)->format('Y-m-d')) }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Instrument Type</label>
                                                <select name="instrument_type_id" class="form-control" style="border: 1px solid #c0c0c0;" required>
                                                    <option value="">Select Instrument Type</option>
                                                    @foreach($instruments as $instrument)
                                                        <option value="{{ $instrument->id }}" {{ old('instrument_type_id', $metadata->instrument_type_id) == $instrument->id ? 'selected' : '' }}>{{ $instrument->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Instrument Sub Type</label>
                                                <select name="instrument_sub_type_id" class="form-control" style="border: 1px solid #c0c0c0;" required>
                                                    <option value="">Select Instrument Sub Type</option>
                                                    @foreach($instrumentTypes as $instrumentType)
                                                        <option value="{{ $instrumentType->id }}" {{ old('instrument_sub_type_id', $metadata->instrument_sub_type_id) == $instrumentType->id ? 'selected' : '' }}>{{ $instrumentType->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Page No. From</label>
                                                <input type="number" name="page_no_from" style="border: 1px solid #c0c0c0;" class="form-control" min="1" value="{{ old('page_no_from', $metadata->page_no_from) }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Page No. To</label>
                                                <input type="number" name="page_no_to" style="border: 1px solid #c0c0c0;" class="form-control" min="1" value="{{ old('page_no_to', $metadata->page_no_to) }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="partyTab" role="tabpanel" aria-labelledby="party-tab">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Party Type</label>
                                                <select name="party_type" class="form-control" style="border: 1px solid #c0c0c0;">
                                                    <option value="">Select Party Type</option>
                                                    <option value="executant" {{ old('party_type', $metadata->party_type) === 'executant' ? 'selected' : '' }}>Executant</option>
                                                    <option value="claimant" {{ old('party_type', $metadata->party_type) === 'claimant' ? 'selected' : '' }}>Claimant</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="party_name" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ old('party_name', $metadata->party_name) }}">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Relation Name</label>
                                                <input type="text" name="relation_name" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ old('relation_name', $metadata->relation_name) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="propertyTab" role="tabpanel" aria-labelledby="property-tab">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">District</label>
                                                <select name="district_id" class="form-control" style="border: 1px solid #c0c0c0;">
                                                    <option value="">Select District</option>
                                                    @foreach($districts as $district)
                                                        <option value="{{ $district->id }}" {{ old('district_id', $metadata->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Registration Office</label>
                                                <select name="vault_registration_office_id" class="form-control" style="border: 1px solid #c0c0c0;">
                                                    <option value="">Select Registration Office</option>
                                                    @foreach($offices as $office)
                                                        <option value="{{ $office->id }}" {{ old('vault_registration_office_id', $metadata->vault_registration_office_id) == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Circle</label>
                                                <input type="text" name="circle" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ old('circle', $metadata->circle) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Village / Thana</label>
                                                <input type="text" name="village" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ old('village', $metadata->village) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Khata No.</label>
                                                <input type="text" name="khata_no" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ old('khata_no', $metadata->khata_no) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Khasra No.</label>
                                                <input type="text" name="khasra_no" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ old('khasra_no', $metadata->khasra_no) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-primary mt-4">Update Metadata</button>
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
