@extends('layouts.app')

@section('title', 'All Deeds')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>All Deeds</h3>
        <p class="text-muted mb-0">Browse all deeds across indexes and filter the list as needed.</p>
    </div>
    @php $user = auth()->user(); @endphp
    @if(isset($index) && $index && $user && $user->isOperator())
        <a href="{{ route('indexes.deeds.create', $index) }}" class="btn btn-success">+ Add Deeds</a>
    @endif
</div>

<div class="card p-3 mb-4">
    <form method="GET" action="{{ route('deeds.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">State</label>
            <select name="state_id" class="form-select">
                <option value="">All states</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ (string) $stateId === (string) $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">District</label>
            <select name="district_id" class="form-select">
                <option value="">All districts</option>
                @foreach($districts as $district)
                    <option value="{{ $district->id }}" {{ (string) $districtId === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Office</label>
            <select name="office_id" class="form-select">
                <option value="">All offices</option>
                @foreach($offices as $office)
                    <option value="{{ $office->id }}" {{ (string) $officeId === (string) $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Volume Year</label>
            <input type="text" name="volume_year" class="form-control" value="{{ $volumeYear }}" placeholder="Filter year">
        </div>
        <div class="col-md-3">
            <label class="form-label">Book</label>
            <input type="text" name="book_number" class="form-control" value="{{ $bookNumber }}" placeholder="Filter book">
        </div>
        <div class="col-md-3">
            <label class="form-label">Volume</label>
            <input type="text" name="volume_number" class="form-control" value="{{ $volumeNumber }}" placeholder="Filter volume">
        </div>
        <div class="col-md-3">
            <label class="form-label">Presentation Year</label>
            <input type="text" name="presentation_year" class="form-control" value="{{ $presentationYear }}" placeholder="Filter deed year">
        </div>
        <div class="col-md-3">
            <label class="form-label">Deed Number</label>
            <input type="text" name="deed_number" class="form-control" value="{{ $deedNumber }}" placeholder="Filter deed number">
        </div>
        <div class="col-md-3">
            <label class="form-label">Party Name</label>
            <input type="text" name="party_name" class="form-control" value="{{ $partyName }}" placeholder="Filter party">
        </div>
        <div class="col-md-3">
            <label class="form-label">Village</label>
            <input type="text" name="village" class="form-control" value="{{ $village }}" placeholder="Filter village">
        </div>
        <div class="col-md-3">
            <label class="form-label">Registration Date</label>
            <input type="date" name="registration_date" class="form-control" value="{{ $registrationDate }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
</div>

<div class="card p-3">
    @if($deeds->isEmpty())
        <div class="alert alert-info mb-0">
            No deeds match the selected filters.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>State</th>
                        <th>District</th>
                        <th>Office</th>
                        <th>Volume Year</th>
                        <th>Book</th>
                        <th>Volume</th>
                        <th>Presentation Year</th>
                        <th>Deed Number</th>
                        <th>Party Name</th>
                        <th>Village</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deeds as $deed)
                        @php $index = $deed->index; @endphp
                        <tr>
                            <td>{{ optional($index?->state)->name ?? '-' }}</td>
                            <td>{{ optional($index?->district)->name ?? '-' }}</td>
                            <td>{{ optional($index?->office)->office_name ?? '-' }}</td>
                            <td>{{ $index?->volume_year ?? '-' }}</td>
                            <td>{{ $index?->book_number ?? '-' }}</td>
                            <td>{{ $index?->volume_number ?? '-' }}</td>
                            <td>{{ $deed->presentation_year ?? '-' }}</td>
                            <td>{{ $deed->deed_number ?? '-' }}</td>
                            <td>{{ $deed->party_name ?? '-' }}</td>
                            <td>{{ $deed->village ?? '-' }}</td>
                            <td>{{ ucfirst($deed->status) }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($deed->scannedDocuments->isNotEmpty())
                                        @php $doc = $deed->scannedDocuments->last(); @endphp
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">View PDF</a>
                                        <a href="{{ route('deeds.download', $deed) }}" class="btn btn-sm btn-outline-success">Download</a>
                                    @endif

                                    @php $user = auth()->user(); @endphp
                                    @if($user && $user->isOperator() && $deed->status == 'approved' && $deed->scannedDocuments->isEmpty())
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning"
                                            data-toggle="modal"
                                            data-target="#scannedCopyModal"
                                            data-deed-id="{{ $deed->id }}"
                                            data-deed-number="{{ $deed->deed_number }}"
                                        >
                                            Upload Scanned Copy
                                        </button>
                                    @endif

                                    @if($index)
                                        @if($user && $user->isChecker())
                                            <a href="{{ route('indexes.deeds.show', [$index, $deed]) }}" class="btn btn-sm btn-info">View</a>
                                        @elseif($deed->status === 'approved')
                                            <a href="{{ route('indexes.deeds.show', [$index, $deed]) }}" class="btn btn-sm btn-info">View</a>
                                        @else
                                            <a href="{{ route('indexes.deeds.edit', [$index, $deed]) }}" class="btn btn-sm btn-secondary">Edit</a>
                                            <form method="POST" action="{{ route('indexes.deeds.destroy', [$index, $deed]) }}" class="d-inline-block" onsubmit="return confirm('Delete this deed?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">No index linked</span>
                                    @endif

                                    @if($deed->metadata)
                                        @if($user && $user->isChecker())
                                            <a href="{{ route('deeds.metadata.show', [$deed, $deed->metadata]) }}" class="btn btn-sm btn-info">View Metadata</a>
                                        @elseif($deed->metadata->status === 'approved')
                                            <a href="{{ route('deeds.metadata.show', [$deed, $deed->metadata]) }}" class="btn btn-sm btn-info">View Metadata</a>
                                        @else
                                            <a href="{{ route('deeds.metadata.edit', [$deed, $deed->metadata]) }}" class="btn btn-sm btn-primary">Edit Metadata</a>
                                        @endif
                                    @else
                                        @if($user && ($user->isOperator() || $user->isAdmin()) && $deed->status == 'approved' && $deed->scannedDocuments->isNotEmpty())
                                            <a href="{{ route('deeds.metadata.create', $deed) }}" class="btn btn-sm btn-info">Create Metadata</a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Upload scanned copy modal -->
<div class="modal fade" id="scannedCopyModal" tabindex="-1" aria-labelledby="scannedCopyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scannedCopyModalLabel">Upload Scanned Copy</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="scannedCopyForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p id="scannedCopyDeedLabel" class="fw-semibold"></p>
                    <div class="mb-3">
                        <label class="form-label">Scanned Copy (PDF)</label>
                        <input type="file" name="scanned_copy" class="form-control" accept="application/pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var scannedCopyModal = document.getElementById('scannedCopyModal');
        if (!scannedCopyModal) {
            return;
        }

        $('#scannedCopyModal').on('show.bs.modal', function (event) {
            debugger;
            var button = $(event.relatedTarget);
            // console.log('Button that triggered the modal:', button);
            // console.log('Button that triggered the modal:', button[0].dataset.deedId);
            var deedId = button[0].dataset.deedId;
            var deedNumber = button[0].dataset.deedNumber;
            // var deedId = button.getAttribute('data-deed-id');
            // var deedNumber = button.getAttribute('data-deed-number');
            var form = document.getElementById('scannedCopyForm');
            // console.log('Form element:', form); 
            var label = document.getElementById('scannedCopyDeedLabel');

            form.action = '/deeds/' + deedId + '/scanned-copy';
            label.textContent = 'Upload scanned PDF for deed #' + deedNumber;
        });
    });
 
</script>
@endsection
