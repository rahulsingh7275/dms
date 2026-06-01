@extends('layouts.app')

@section('title', 'Edit Instrument Type')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <h3 class="mb-4">Edit Instrument Type</h3>
            <form method="POST" action="{{ route('admin.instrument-types.update', $instrumentType) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Instrument</label>
                    <select name="instrument_id" class="form-select" required>
                        <option value="">Select instrument</option>
                        @foreach($instruments as $instrument)
                            <option value="{{ $instrument->id }}" {{ old('instrument_id', $instrumentType->instrument_id) == $instrument->id ? 'selected' : '' }}>{{ $instrument->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Instrument Type Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $instrumentType->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $instrumentType->code) }}">
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.instrument-types.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Instrument Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
