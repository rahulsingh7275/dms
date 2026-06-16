@extends('layouts.app')

@section('title', 'Create Index')

@section('content')
<div class="card p-4">
    <h3>Create Index</h3>
    <form method="POST" action="{{ route('indexes.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">State</label>
                <select name="state_id" id="state_id" style="border: 1px solid #c0c0c0;" class="form-control" required>
                    <option value="">Select State</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">District</label>
                <select name="district_id" id="district_id" style="border: 1px solid #c0c0c0;" class="form-control" required>
                    <option value="">Select District</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Registration Office</label>
                <select name="vault_registration_office_id" id="vault_registration_office_id" style="border: 1px solid #c0c0c0;" class="form-control" required>
                    <option value="">Select Office</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Volume Year</label>
                <input type="text" name="volume_year" style="border: 1px solid #c0c0c0;" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Book No.</label>
                <input type="text" name="book_number" style="border: 1px solid #c0c0c0;" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Volume No.</label>
                <input type="text" name="volume_number" style="border: 1px solid #c0c0c0;" class="form-control" required>
            </div>
            <div class="col-md-12 form-check mt-3">
                <input class="form-check-input" type="checkbox" name="is_volume_forwarded" id="is_forwarded" value="1">
                <label class="form-check-label" for="is_forwarded">Volume Forwarded</label>
            </div>
        </div>
        <button class="btn btn-primary mt-4">Create Index</button>
    </form>
</div>
@endsection
