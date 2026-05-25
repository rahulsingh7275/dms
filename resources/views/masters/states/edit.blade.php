@extends('layouts.app')

@section('title', 'Edit State')

@section('content')
<div class="card p-4">
    <h3>Edit State</h3>
    <form method="POST" action="{{ route('states.update', $state) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">State Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $state->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">State Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $state->code) }}">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ $state->status ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
        </div>
        <button class="btn btn-primary">Update State</button>
    </form>
</div>
@endsection
