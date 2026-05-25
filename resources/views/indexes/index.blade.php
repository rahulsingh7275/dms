@extends('layouts.app')

@section('title', 'Indexes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Indexes</h3>
    <a href="{{ route('indexes.create') }}" class="btn btn-primary">Add Index</a>
</div>

<div class="card p-3 mb-4">
    <form method="GET" action="{{ route('indexes.index') }}" class="row g-3 align-items-end">
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
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
</div>

<div class="card p-3">
    @if($indexes->isEmpty())
        <div class="alert alert-info mb-0">
            No indexes match the selected filters.
        </div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>State</th>
                    <th>District</th>
                    <th>Office</th>
                    <th>Volume Year</th>
                    <th>Book</th>
                    <th>Volume</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($indexes as $index)
                    <tr>
                        <td>{{ $index->state->name }}</td>
                        <td>{{ $index->district->name }}</td>
                        <td>{{ $index->office->office_name }}</td>
                        <td>{{ $index->volume_year }}</td>
                        <td>{{ $index->book_number }}</td>
                        <td>{{ $index->volume_number }}</td>
                        <td>{{ ucfirst($index->status) }}</td>
                        <td>
                            @php $user = auth()->user(); @endphp
                            @if($user && ($user->isOperator() || $user->isAdmin()))
                                <a href="{{ route('indexes.edit', $index) }}" class="btn btn-sm btn-secondary">Edit</a>
                                @if($index->status === 'approved')
                                    <a href="{{ route('indexes.deeds.index', $index) }}" class="btn btn-sm btn-info">Deeds</a>
                                @endif
                                <form method="POST" action="{{ route('indexes.destroy', $index) }}" class="d-inline-block" onsubmit="return confirm('Delete this index?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @elseif($user && $user->isChecker())
                                <a href="{{ route('indexes.show', $index) }}" class="btn btn-sm btn-info">View</a>
                            @else
                                <a href="{{ route('indexes.edit', $index) }}" class="btn btn-sm btn-secondary">Edit</a>
                                @if($index->status === 'approved')
                                    <a href="{{ route('indexes.deeds.index', $index) }}" class="btn btn-sm btn-info">Deeds</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
