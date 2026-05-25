@extends('layouts.app')

@section('title', 'Add Metadata')

@section('content')
<div class="card p-4">
    <h3>Add Metadata for Deed #{{ $deed->id }}</h3>
    <form method="POST" action="{{ route('deeds.metadata.store', $deed) }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Presentation Year</label>
                <input type="text" name="presentation_year" class="form-control" value="{{ old('presentation_year') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Deed Number</label>
                <input type="text" name="deed_number" class="form-control" value="{{ old('deed_number') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Party Name</label>
                <input type="text" name="party_name" class="form-control" value="{{ old('party_name') }}">
            </div>
            <div class="col-md-12">
                <label class="form-label">Property Details</label>
                <textarea name="property_details" class="form-control">{{ old('property_details') }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Village</label>
                <input type="text" name="village" class="form-control" value="{{ old('village') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Area</label>
                <input type="text" name="area" class="form-control" value="{{ old('area') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Registration Date</label>
                <input type="date" name="registration_date" class="form-control" value="{{ old('registration_date') }}">
            </div>
        </div>
        <button class="btn btn-primary mt-4">Save Metadata</button>
    </form>
</div>
@endsection
