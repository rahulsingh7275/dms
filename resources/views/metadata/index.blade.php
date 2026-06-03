@extends('layouts.app')

@section('title', 'Metadata List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Metadata List</h3>
        <p class="text-muted mb-0">All saved metadata entries across volumes.</p>
    </div>
</div>

<div class="card p-3 mb-4">
    <form method="GET" action="{{ route('metadata.index') }}" class="row g-3 align-items-end">
        <div class="col-sm-3">
            <span style="color: black;">State:</span>
            <select name="state_id" id="state_id" style="border: 1px solid #c0c0c0;" class="form-control">
                <option value="">All states</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ (string) $stateId === (string) $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <span style="color: black;">District:</span>
            <select name="district_id" id="district_id" style="border: 1px solid #c0c0c0;" class="form-control">
                <option value="">All districts</option>
                @foreach($districts as $district)
                    <option value="{{ $district->id }}" {{ (string) $districtId === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <span style="color: black;">Office:</span>
            <select name="office_id" id="office_id" style="border: 1px solid #c0c0c0;" class="form-control">
                <option value="">All offices</option>
                @foreach($offices as $office)
                    <option value="{{ $office->id }}" {{ (string) $officeId === (string) $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <span style="color: black;">Status:</span>
            <select name="status" id="status" style="border: 1px solid #c0c0c0;" class="form-control">
                <option value="">All statuses</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Volume</label>
            <input type="text" name="volume" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $volume }}" placeholder="Search volume/year">
        </div>
        <div class="col-md-3">
            <label class="form-label">Book</label>
            <input type="text" name="book" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $book }}" placeholder="Filter book">
        </div>
        <div class="col-md-3">
            <label class="form-label">Deed Number</label>
            <input type="text" name="deed_number" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $deedNumber }}" placeholder="Filter deed number">
        </div>
        <div class="col-md-3">
            <label class="form-label">Presentation Year</label>
            <input type="text" name="presentation_year" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $presentationYear }}" placeholder="Filter year">
        </div>
        <div class="col-md-3">
            <label class="form-label">Party Name</label>
            <input type="text" name="party_name" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $partyName }}" placeholder="Filter party">
        </div>
        <div class="col-md-3">
            <label class="form-label">Village</label>
            <input type="text" name="village" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $village }}" placeholder="Filter village">
        </div>
        <div class="col-md-3">
            <label class="form-label">Registration Date</label>
            <input type="date" name="date" style="border: 1px solid #c0c0c0;" class="form-control" value="{{ $date }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-sm w-100" style="padding: 0.45rem 0.75rem;">Filter</button>
        </div>
    </form>
</div>

<div class="card p-3">
    @if($metadataList->isEmpty())
        <div class="alert alert-info mb-0">
            No metadata matches the selected filters.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>State</th>
                        <th>District</th>
                        <th>Office</th>
                        <th>Volume</th>
                        <th>Book</th>
                        <th>Deed Number</th>
                        <th>Presentation Year</th>
                        <th>Party Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($metadataList as $metadata)
                        @php
                            $deed = $metadata->deed;
                            $index = $deed?->index;
                        @endphp
                        <tr>
                            <td>{{ optional($index?->state)->name ?? '-' }}</td>
                            <td>{{ optional($index?->district)->name ?? '-' }}</td>
                            <td>{{ optional($index?->office)->office_name ?? '-' }}</td>
                            <td>{{ $index?->volume_number ?? '-' }}</td>
                            <td>{{ $index?->book_number ?? '-' }}</td>
                            <td>{{ $metadata->deed_number ?? '-' }}</td>
                            <td>{{ $metadata->presentation_year ?? '-' }}</td>
                            <td>{{ $metadata->party_name ?? '-' }}</td>
                            <td>{{ ucfirst($metadata->status) }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($deed)
                                        @php $user = auth()->user(); @endphp
                                        @if($metadata->status === 'approved' || ($user && $user->isChecker()))
                                            <a href="{{ route('deeds.metadata.show', [$deed, $metadata]) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                        @else
                                            <a href="{{ route('deeds.metadata.edit', [$deed, $metadata]) }}" class="btn btn-sm btn-primary">Edit</a>
                                           
                                            <form method="POST" action="{{ route('deeds.metadata.destroy', [$deed, $metadata]) }}" class="d-inline-block" onsubmit="return confirm('Delete this metadata?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">No deed linked</span>
                                    @endif

                                    @if($index)
                                        <a href="{{ route('indexes.deeds.index', $index) }}" class="btn btn-sm btn-outline-secondary">View Deed</a>
                                    @else
                                        <span class="badge bg-light text-dark">No volume linked</span>
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
@endsection
