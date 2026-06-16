@extends('layouts.app')

@section('title', 'View Metadata')

@section('content')
@php
    $index = $deed->index;
    $latestScannedDocument = $deed->scannedDocuments->last();
    $latestIndexVerification = $index?->indexVerifications->sortByDesc('verified_at')->first();
    $latestQcVerification = $index?->qcVerifications->sortByDesc('verified_at')->first();
    $latestMetadataVerification = $metadata->verifications->sortByDesc('verified_at')->first();
    $user = auth()->user();
@endphp

<div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1">Metadata Review</p>
            <h3 class="mb-0">View Metadata #{{ $metadata->id }} for Deed #{{ $deed->id }}</h3>
            <p class="text-muted">Index: #{{ $deed->index->id ?? '-' }} — {{ $deed->index->state->name ?? '-' }} / {{ $deed->index->district->name ?? '-' }} / {{ $deed->index->office->office_name ?? '-' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('indexes.deeds.index', $deed->index) }}" class="btn btn-secondary">Back</a>
            @if($user && ($user->isOperator() || $user->isAdmin()) && $metadata->status !== 'approved')
                <a href="{{ route('deeds.metadata.edit', [$deed, $metadata]) }}" class="btn btn-primary">Edit Metadata</a>
            @endif
        </div>
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
                            <h5 class="mb-3">Metadata Details</h5>
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
                                            <label class="form-label">Presentation Year</label>
                                            <input type="text" class="form-control" value="{{ $metadata->presentation_year }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Deed Number</label>
                                            <input type="text" class="form-control" value="{{ $metadata->deed_number }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Presection Date</label>
                                            <input type="date" class="form-control" value="{{ optional($metadata->presection_date)->format('Y-m-d') }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Registration Date</label>
                                            <input type="date" class="form-control" value="{{ optional($metadata->registration_date)->format('Y-m-d') }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Instrument Type</label>
                                            <input type="text" class="form-control" value="{{ $metadata->instrumentType?->name ?? ($metadata->instrument_type_id ? 'ID: '.$metadata->instrument_type_id : '-') }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Instrument Sub Type</label>
                                            <input type="text" class="form-control" value="{{ $metadata->instrumentSubType?->name ?? ($metadata->instrument_sub_type_id ? 'ID: '.$metadata->instrument_sub_type_id : '-') }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Page No. From</label>
                                            <input type="number" class="form-control" value="{{ $metadata->page_no_from }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Page No. To</label>
                                            <input type="number" class="form-control" value="{{ $metadata->page_no_to }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="partyTab" role="tabpanel" aria-labelledby="party-tab">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Party Type</label>
                                            <input type="text" class="form-control" value="{{ ucfirst($metadata->party_type ?? '') }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" value="{{ $metadata->party_name }}" disabled>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Relation Name</label>
                                            <input type="text" class="form-control" value="{{ $metadata->relation_name }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="propertyTab" role="tabpanel" aria-labelledby="property-tab">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">District</label>
                                            <input type="text" class="form-control" value="{{ $metadata->district?->name ?? ($metadata->district_id ? 'ID: '.$metadata->district_id : '-') }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Registration Office</label>
                                            <input type="text" class="form-control" value="{{ $metadata->vaultRegistrationOffice?->office_name ?? ($metadata->vault_registration_office_id ? 'ID: '.$metadata->vault_registration_office_id : '-') }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Circle</label>
                                            <input type="text" class="form-control" value="{{ $metadata->circle }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Village / Thana</label>
                                            <input type="text" class="form-control" value="{{ $metadata->village }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Khata No.</label>
                                            <input type="text" class="form-control" value="{{ $metadata->khata_no }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Khasra No.</label>
                                            <input type="text" class="form-control" value="{{ $metadata->khasra_no }}" disabled>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Property Details</label>
                                            <textarea class="form-control" rows="4" disabled>{{ $metadata->property_details }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

    <!-- Verification section moved to bottom -->
    @if($user && $user->isChecker() && $metadata->status === 'pending')
        <div class="card p-3 mb-4 border-info">
            <h5 class="mb-3">Verify Metadata Status</h5>
            <div class="d-flex flex-wrap gap-2">
                <form method="POST" action="{{ route('deeds.metadata.status.update', [$deed, $metadata]) }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="status" value="pending">
                    <button type="submit" class="btn-sm btn-warning mx-2" title="Mark as Under Process">
                        <i class="fa fa-hourglass"></i> Under Process
                    </button>
                </form>

                <button type="button" class="btn-sm btn-danger mx-2" data-toggle="modal" data-target="#rejectModal" title="Reject with comment">
                    <i class="fa fa-times"></i> Reject
                </button>

                <form method="POST" action="{{ route('deeds.metadata.status.update', [$deed, $metadata]) }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn-sm btn-success mx-2" title="Approve metadata">
                        <i class="fa fa-check"></i> Approve
                    </button>
                </form>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reject Metadata #{{ $metadata->id }}</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('deeds.metadata.status.update', [$deed, $metadata]) }}">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label"><strong>Metadata Details</strong></label>
                                <div class="p-2 bg-light rounded">
                                    <p><strong>Party Name:</strong> {{ $metadata->party_name }}</p>
                                    <p><strong>Deed Number:</strong> {{ $metadata->deed_number }}</p>
                                    <p><strong>Presentation Year:</strong> {{ $metadata->presentation_year }}</p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><strong>Rejection Reason <span class="text-danger">*</span></strong></label>
                                <textarea name="comment" class="form-control" rows="4" placeholder="Please provide reason for rejection..." required></textarea>
                                <small class="text-muted">This comment will be saved in the system.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="card p-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            @if($user && ($user->isOperator() || $user->isAdmin()) && $metadata->status !== 'approved')
                <a href="{{ route('deeds.metadata.edit', [$deed, $metadata]) }}" class="btn btn-primary">Edit Metadata</a>
                <form method="POST" action="{{ route('deeds.metadata.destroy', [$deed, $metadata]) }}" class="d-inline-block" onsubmit="return confirm('Delete this metadata?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete Metadata</button>
                </form>
            @else
                <span class="badge bg-secondary">Read-only view</span>
            @endif
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