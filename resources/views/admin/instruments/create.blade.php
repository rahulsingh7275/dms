@extends('layouts.app')

@section('title', 'Add Instrument')

@section('content')
<div class="card p-4">
    <h3>Create Instrument</h3>
    <form method="POST" action="{{ route('admin.instruments.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Instrument Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code') }}">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
            <label class="form-check-label" for="status">Active</label>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-md btn-primary">Save Instrument</button>
            <a href="{{ route('admin.instruments.index') }}" class="btn btn-md btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
