@extends('layouts.app')

@section('title', 'Deeds')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Deeds for Index #{{ $index->id }}</h3>
        <p class="text-muted">{{ $index->state->name }} / {{ $index->district->name }} / {{ $index->office->office_name }}</p>
    </div>
    <a href="{{ route('indexes.deeds.create', $index) }}" class="btn btn-primary">Add Deed</a>
</div>

<div class="card p-3 mb-4">
    <form method="GET" action="{{ route('indexes.deeds.index', $index) }}" class="row g-3 align-items-end">
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
            <label class="form-label">Presentation Year</label>
            <input type="text" name="presentation_year" class="form-control" value="{{ $presentationYear }}" placeholder="Filter year">
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
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Presentation Year</th>
                    <th>Deed Number</th>
                    <th>Party Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deeds as $deed)
                    <tr>
                        <td>{{ $deed->presentation_year }}</td>
                        <td>{{ $deed->deed_number }}</td>
                        <td>{{ $deed->party_name }}</td>
                        <td>{{ ucfirst($deed->status) }}</td>
                        <td>
                            @if($deed->scannedDocuments->isNotEmpty())
                                @php $doc = $deed->scannedDocuments->last(); @endphp
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">View PDF</a>
                                <a href="{{ route('deeds.download', $deed) }}" class="btn btn-sm btn-outline-success me-1">Download</a>
                            @endif
                            <a href="{{ route('indexes.deeds.edit', [$index, $deed]) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <a href="{{ route('deeds.metadata.create', $deed) }}" class="btn btn-sm btn-info">Metadata</a>
                            <form method="POST" action="{{ route('indexes.deeds.destroy', [$index, $deed]) }}" class="d-inline-block" onsubmit="return confirm('Delete this deed?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
