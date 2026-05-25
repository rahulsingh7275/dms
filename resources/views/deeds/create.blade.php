@extends('layouts.app')

@section('title', 'Add Deed')

@section('content')
<div class="card p-4">
    <h3>Add Deed for Index #{{ $index->id }}</h3>
    <form method="POST" action="{{ route('indexes.deeds.store', $index) }}" enctype="multipart/form-data">
        @csrf
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Presentation Year</label>
                <input type="text" name="presentation_year" class="form-control @error('presentation_year') is-invalid @enderror" value="{{ old('presentation_year') }}" required>
                @error('presentation_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Deed Number</label>
                <input type="text" name="deed_number" class="form-control @error('deed_number') is-invalid @enderror" value="{{ old('deed_number') }}" required>
                @error('deed_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Party Name</label>
                <input type="text" name="party_name" class="form-control @error('party_name') is-invalid @enderror" value="{{ old('party_name') }}">
                @error('party_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Property Details</label>
                <textarea name="property_details" class="form-control @error('property_details') is-invalid @enderror">{{ old('property_details') }}</textarea>
                @error('property_details')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Village</label>
                <input type="text" name="village" class="form-control @error('village') is-invalid @enderror" value="{{ old('village') }}">
                @error('village')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Area</label>
                <input type="text" name="area" class="form-control @error('area') is-invalid @enderror" value="{{ old('area') }}">
                @error('area')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Registration Date</label>
                <input type="date" name="registration_date" class="form-control @error('registration_date') is-invalid @enderror" value="{{ old('registration_date') }}">
                @error('registration_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Scanned Copy (PDF)</label>
                <input type="file" name="scanned_copy" class="form-control @error('scanned_copy') is-invalid @enderror" accept="application/pdf">
                @error('scanned_copy')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Save Deed</button>
    </form>
</div>
@endsection
