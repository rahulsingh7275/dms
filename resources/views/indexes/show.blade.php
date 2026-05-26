@extends('layouts.app')

@section('title', 'View Index')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Index #{{ $index->id }} Details</h3>
        <p class="text-muted">{{ $index->state->name ?? '-' }} / {{ $index->district->name ?? '-' }} / {{ $index->office->office_name ?? '-' }}</p>
    </div>
    <a href="{{ route('indexes.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card p-3 mb-4">
    <dl class="row">
        <dt class="col-sm-3">Volume Year</dt>
        <dd class="col-sm-9">{{ $index->volume_year }}</dd>

        <dt class="col-sm-3">Book Number</dt>
        <dd class="col-sm-9">{{ $index->book_number }}</dd>

        <dt class="col-sm-3">Volume Number</dt>
        <dd class="col-sm-9">{{ $index->volume_number }}</dd>

        <dt class="col-sm-3">Status</dt>
        <dd class="col-sm-9">{{ ucfirst($index->status) }}</dd>
        @php $latestVerification = $index->indexVerifications->sortByDesc('verified_at')->first(); @endphp
        <dt class="col-sm-3">Latest Remark</dt>
        <dd class="col-sm-9">{{ $latestVerification->remarks ?? '-' }}</dd>
        <dt class="col-sm-3">Locked</dt>
        <dd class="col-sm-9">{{ $index->locked ? 'Yes' : 'No' }}</dd>
        <dt class="col-sm-3">Created By</dt>
        <dd class="col-sm-9">{{ $index->creator?->name ?? $index->created_by ?? '-' }}</dd>
        <dt class="col-sm-3">Created At</dt>
        <dd class="col-sm-9">{{ $index->created_at }}</dd>
        <dt class="col-sm-3">Updated At</dt>
        <dd class="col-sm-9">{{ $index->updated_at }}</dd>
    </dl>
</div>

@php $user = auth()->user(); @endphp
@if($user && $user->isChecker() && $index->status === 'pending')
    <div class="card p-3 mb-4 border-info">
        <h5 class="mb-3">Verify Index Status</h5>
        <div class="d-flex flex-wrap gap-2">
            <!-- Under Process Button -->
            <form method="POST" action="{{ route('indexes.status.update', $index) }}" style="display:inline;">
                @csrf
                <input type="hidden" name="status" value="pending">
                <button type="submit" class="btn btn-warning" title="Mark as Under Process">
                    <i class="fas fa-hourglass"></i> Under Process
                </button>
            </form>
            
            <!-- Reject Button (with modal) -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectIndexModal" title="Reject with comment">
                <i class="fas fa-times"></i> Reject
            </button>
            
            <!-- Approve Button -->
            <form method="POST" action="{{ route('indexes.status.update', $index) }}" style="display:inline;">
                @csrf
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn btn-success" title="Approve index">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectIndexModal" tabindex="-1" aria-labelledby="rejectIndexModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectIndexModalLabel">
                        Reject Index #{{ $index->id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('indexes.status.update', $index) }}">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label"><strong>Index Details</strong></label>
                            <div class="p-2 bg-light rounded">
                                <p><strong>Volume Year:</strong> {{ $index->volume_year }}</p>
                                <p><strong>Book Number:</strong> {{ $index->book_number }}</p>
                                <p><strong>Volume Number:</strong> {{ $index->volume_number }}</p>
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

<div class="card p-3">
    <h5>Deeds in this Index</h5>
    @if($index->deeds->isEmpty())
        <div class="alert alert-info">No deeds available.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Presentation Year</th>
                    <th>Deed Number</th>
                    <th>Party Name</th>
                    <th>Status</th>
                    <th>Latest Remark</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($index->deeds as $deed)
                    @php $latestDeedVerification = $deed->deedVerifications->sortByDesc('verified_at')->first(); @endphp
                    <tr>
                        <td>{{ $deed->presentation_year }}</td>
                        <td>{{ $deed->deed_number }}</td>
                        <td>{{ $deed->party_name }}</td>
                        <td>
                            <span class="badge bg-{{ $deed->status === 'approved' ? 'success' : ($deed->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($deed->status) }}
                            </span>
                        </td>
                        <td>
                            @if($latestDeedVerification && $latestDeedVerification->remarks)
                                <small class="text-muted">{{ $latestDeedVerification->remarks }}</small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            @php $user = auth()->user(); @endphp
                            @if($user && $user->isChecker() && $deed->status === 'pending')
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Under Process Button -->
                                    <form method="POST" action="{{ route('indexes.deeds.status.update', [$index, $deed]) }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" class="btn btn-warning btn-sm" title="Mark as Under Process">
                                            <i class="fas fa-hourglass"></i> Process
                                        </button>
                                    </form>
                                    
                                    <!-- Reject Button (with modal) -->
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $deed->id }}" title="Reject with comment">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                    
                                    <!-- Approve Button -->
                                    <form method="POST" action="{{ route('indexes.deeds.status.update', [$index, $deed]) }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-success btn-sm" title="Approve deed">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Reject Modals for each deed -->
@php $user = auth()->user(); @endphp
@if($user && $user->isChecker())
    @foreach($index->deeds as $deed)
        @if($deed->status === 'pending')
            <div class="modal fade" id="rejectModal-{{ $deed->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $deed->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel-{{ $deed->id }}">
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
    @endforeach
@endif
@endsection
