@extends('layouts.app')

@section('title', 'View Metadata')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Metadata #{{ $metadata->id }} for Deed #{{ $deed->id }}</h3>
        <p class="text-muted">Index: #{{ $deed->index->id ?? '-' }} — {{ $deed->index->state->name ?? '-' }} / {{ $deed->index->district->name ?? '-' }} / {{ $deed->index->office->office_name ?? '-' }}</p>
    </div>
    <a href="{{ route('metadata.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card p-4 mb-4">
    <dl class="row">
        <dt class="col-sm-3">Presentation Year</dt>
        <dd class="col-sm-9">{{ $metadata->presentation_year }}</dd>

        <dt class="col-sm-3">Deed Number</dt>
        <dd class="col-sm-9">{{ $metadata->deed_number }}</dd>

        <dt class="col-sm-3">Party Name</dt>
        <dd class="col-sm-9">{{ $metadata->party_name }}</dd>

        <dt class="col-sm-3">Property Details</dt>
        <dd class="col-sm-9">{{ $metadata->property_details }}</dd>

        <dt class="col-sm-3">Village</dt>
        <dd class="col-sm-9">{{ $metadata->village }}</dd>

        <dt class="col-sm-3">Area</dt>
        <dd class="col-sm-9">{{ $metadata->area }}</dd>

        <dt class="col-sm-3">Registration Date</dt>
        <dd class="col-sm-9">{{ optional($metadata->registration_date)->format('Y-m-d') ?? '-' }}</dd>

        <dt class="col-sm-3">Status</dt>
        <dd class="col-sm-9">{{ ucfirst($metadata->status) }}</dd>

        <dt class="col-sm-3">Latest Remark</dt>
        <dd class="col-sm-9">
            @php $latestMetadataVerification = $metadata->verifications->sortByDesc('verified_at')->first(); @endphp
            @if($latestMetadataVerification && $latestMetadataVerification->remarks)
                <small class="text-muted">{{ $latestMetadataVerification->remarks }}</small>
            @else
                <small class="text-muted">-</small>
            @endif
        </dd>
    </dl>
</div>

<!-- Metadata Verification Section for Checkers -->
@php $user = auth()->user(); @endphp
@if($user && $user->isChecker() && $metadata->status === 'pending')
    <div class="card p-3 mb-4 border-info">
        <h5 class="mb-3">Verify Metadata Status</h5>
        <div class="d-flex flex-wrap gap-2">
            <!-- Under Process Button -->
            <form method="POST" action="{{ route('deeds.metadata.status.update', [$deed, $metadata]) }}" style="display:inline;">
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
            <form method="POST" action="{{ route('deeds.metadata.status.update', [$deed, $metadata]) }}" style="display:inline;">
                @csrf
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn btn-success" title="Approve metadata">
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
                        Reject Metadata #{{ $metadata->id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
@endsection