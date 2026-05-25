@extends('layouts.app')

@section('title', 'All Deeds')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>All Deeds</h3>
        <p class="text-muted mb-0">Browse all deeds across indexes and filter the list as needed.</p>
    </div>
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

                                    @if($index)
                                        <a href="{{ route('indexes.deeds.edit', [$index, $deed]) }}" class="btn btn-sm btn-secondary">Edit</a>
                                        <form method="POST" action="{{ route('indexes.deeds.destroy', [$index, $deed]) }}" class="d-inline-block" onsubmit="return confirm('Delete this deed?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary">No index linked</span>
                                    @endif

                                    <a href="{{ route('deeds.metadata.create', $deed) }}" class="btn btn-sm btn-info">Metadata</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
