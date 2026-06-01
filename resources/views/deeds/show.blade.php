@extends('layouts.app')

@section('title', 'View Deed')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Deed #{{ $deed->id }} Details</h3>
        <p class="text-muted">Index: #{{ $index->id }} — {{ $index->state->name ?? '-' }} / {{ $index->district->name ?? '-' }} / {{ $index->office->office_name ?? '-' }}</p>
    </div>
    <a href="{{ route('indexes.deeds.index', $index) }}" class="btn btn-secondary">Back</a>
</div>

<div class="card p-4 mb-4">
    <dl class="row">
        <dt class="col-sm-3">Presentation Year</dt>
        <dd class="col-sm-9">{{ $deed->presentation_year }}</dd>

        <dt class="col-sm-3">Deed Number</dt>
        <dd class="col-sm-9">{{ $deed->deed_number }}</dd>

        <dt class="col-sm-3">Party Name</dt>
        <dd class="col-sm-9">{{ $deed->party_name }}</dd>

        <dt class="col-sm-3">Property Details</dt>
        <dd class="col-sm-9">{{ $deed->property_details }}</dd>

        <dt class="col-sm-3">Village</dt>
        <dd class="col-sm-9">{{ $deed->village }}</dd>

        <dt class="col-sm-3">Area</dt>
        <dd class="col-sm-9">{{ $deed->area }}</dd>

        <dt class="col-sm-3">Registration Date</dt>
        <dd class="col-sm-9">{{ optional($deed->registration_date)->format('Y-m-d') ?? '-' }}</dd>

        <dt class="col-sm-3">Status</dt>
        <dd class="col-sm-9">{{ ucfirst($deed->status) }}</dd>

        <dt class="col-sm-3">Scanned Document</dt>
        <dd class="col-sm-9">
            @if($deed->scannedDocuments->isNotEmpty())
                @php $doc = $deed->scannedDocuments->last(); @endphp
                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">View PDF</a>
                <a href="{{ route('deeds.download', $deed) }}" class="btn btn-sm btn-outline-success">Download</a>
            @else
                -
            @endif
        </dd>

        <dt class="col-sm-3">Metadata</dt>
        <dd class="col-sm-9">
            @if($deed->metadata)
                {{ ucfirst($deed->metadata->status) }}
            @else
                No metadata yet
            @endif
        </dd>

        <dt class="col-sm-3">Latest Remark</dt>
        <dd class="col-sm-9">
            @php $latestDeedVerification = $deed->deedVerifications->sortByDesc('verified_at')->first(); @endphp
            @if($latestDeedVerification && $latestDeedVerification->remarks)
                <small class="text-muted">{{ $latestDeedVerification->remarks }}</small>
            @else
                <small class="text-muted">-</small>
            @endif
        </dd>
    </dl>
</div>

<!-- Deed Verification Section for Checkers -->
@php $user = auth()->user(); @endphp
@if($user && $user->isChecker() && $deed->status === 'pending')
    <div class="card p-3 mb-4 border-info">
        <h5 class="mb-3">Verify Deed Status</h5>
        <div class="d-flex flex-wrap gap-2">
            <!-- Under Process Button -->
            <form method="POST" action="{{ route('indexes.deeds.status.update', [$index, $deed]) }}" style="display:inline;">
                @csrf
                <input type="hidden" name="status" value="pending">
                <button type="submit" class="btn btn-warning" title="Mark as Under Process">
                    <i class="fas fa-hourglass"></i> Under Process
                </button>
            </form>
            
            <!-- Reject Button (with modal) -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" title="Reject with comment">
                <i class="fas fa-times"></i> Reject
            </button>
            
            <!-- Approve Button -->
            <form method="POST" action="{{ route('indexes.deeds.status.update', [$index, $deed]) }}" style="display:inline;">
                @csrf
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn btn-success" title="Approve deed">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">
                        Reject Deed #{{ $deed->deed_number }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('indexes.deeds.status.update', [$index, $deed]) }}">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label"><strong>Deed Details</strong></label>
                            <div class="p-2 bg-light rounded">
                                <p><strong>Party Name:</strong> {{ $deed->party_name }}</p>
                                <p><strong>Deed Number:</strong> {{ $deed->deed_number }}</p>
                                <p><strong>Presentation Year:</strong> {{ $deed->presentation_year }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Rejection Reason <span class="text-danger">*</span></strong></label>
                            <textarea name="comment" class="form-control" rows="4" placeholder="Please provide reason for rejection..." required></textarea>
                            <small class="text-muted">This comment will be saved in the system.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<div class="card p-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
        @if($user && ($user->isOperator() || $user->isAdmin()) && $deed->status !== 'approved')
            <a href="{{ route('indexes.deeds.edit', [$index, $deed]) }}" class="btn btn-secondary">Edit Deed</a>
            <form method="POST" action="{{ route('indexes.deeds.destroy', [$index, $deed]) }}" class="d-inline-block" onsubmit="return confirm('Delete this deed?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete Deed</button>
            </form>
        @else
            <span class="badge bg-secondary">Read-only view</span>
        @endif

        @if($deed->metadata)
            <a href="{{ route('deeds.metadata.show', [$deed, $deed->metadata]) }}" class="btn btn-info">View Metadata</a>
        @elseif($user && ($user->isOperator() || $user->isAdmin()))
            <a href="{{ route('deeds.metadata.create', $deed) }}" class="btn btn-info">Create Metadata</a>
        @endif
    </div>
</div>
@endsection