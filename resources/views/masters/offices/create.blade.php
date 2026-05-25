@extends('layouts.app')

@section('title', 'Create Registration Office')

@section('content')
<div class="card p-4">
    <h3>Create Vault Registration Office</h3>
    <form method="POST" action="{{ route('offices.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">District</label>
            <select name="district_id" class="form-select" required>
                <option value="">Select District</option>
                @foreach($districts as $district)
                    <option value="{{ $district->id }}">{{ $district->state->name }} / {{ $district->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Office Name</label>
            <input type="text" name="office_name" class="form-control" value="{{ old('office_name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Office Code</label>
            <input type="text" name="office_code" class="form-control" value="{{ old('office_code') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control">{{ old('address') }}</textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
            <label class="form-check-label" for="status">Active</label>
        </div>
        <button class="btn btn-primary">Save Office</button>
    </form>
</div>
@endsection
