@extends('layouts.app')

@section('title', 'Edit District')

@section('content')
<div class="card p-4">
    <h3 class="mb-4">Edit District</h3>
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-dismiss="alert"></button>
        </div>
    @endif
    <form method="POST" action="{{ route('districts.update', $district) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">State</label>
            <select name="state_id" class="form-control" required>
                @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ $district->state_id === $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">District Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $district->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">District Code</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $district->code) }}">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ $district->status ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-md btn-primary">Update District</button>
            <a href="{{ route('districts.index') }}" class="btn btn-md btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
