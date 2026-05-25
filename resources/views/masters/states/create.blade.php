@extends('layouts.app')

@section('title', 'Create State')

@section('content')
<div class="card p-4">
    <h3>Create State</h3>
    <form method="POST" action="{{ route('states.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">State Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">State Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code') }}">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
            <label class="form-check-label" for="status">Active</label>
        </div>
        <button class="btn btn-primary">Save State</button>
    </form>
</div>
@endsection
