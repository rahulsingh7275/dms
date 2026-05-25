@extends('layouts.app')

@section('title', 'Edit Index')

@section('content')
<div class="card p-4">
    <h3>Edit Index</h3>
    <form method="POST" action="{{ route('indexes.update', $index) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">State</label>
                <select name="state_id" class="form-select" required>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}" {{ $index->state_id === $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">District</label>
                <select name="district_id" class="form-select" required>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ $index->district_id === $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Registration Office</label>
                <select name="vault_registration_office_id" class="form-select" required>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}" {{ $index->vault_registration_office_id === $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Volume Year</label>
                <input type="text" name="volume_year" class="form-control" value="{{ old('volume_year', $index->volume_year) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Book No.</label>
                <input type="text" name="book_number" class="form-control" value="{{ old('book_number', $index->book_number) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Volume No.</label>
                <input type="text" name="volume_number" class="form-control" value="{{ old('volume_number', $index->volume_number) }}" required>
            </div>
            <div class="col-md-12 form-check mt-3">
                <input class="form-check-input" type="checkbox" name="is_volume_forwarded" id="is_forwarded" value="1" {{ $index->is_volume_forwarded ? 'checked' : '' }}>
                <label class="form-check-label" for="is_forwarded">Volume Forwarded</label>
            </div>
        </div>
        <button class="btn btn-primary mt-4">Update Index</button>
    </form>
</div>
@endsection
