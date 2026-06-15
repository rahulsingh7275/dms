@extends('layouts.app')

@section('title', 'Add Instrument')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <h3 class="mb-4">Add Instrument</h3>
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
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.instruments.index') }}" class="btn btn-md btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-md btn-primary">Save Instrument</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
