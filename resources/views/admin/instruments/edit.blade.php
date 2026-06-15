@extends('layouts.app')

@section('title', 'Edit Instrument')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <h3 class="mb-4">Edit Instrument</h3>
            <form method="POST" action="{{ route('admin.instruments.update', $instrument) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Instrument Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $instrument->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $instrument->code) }}">
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.instruments.index') }}" class="btn btn-md btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-md btn-primary">Update Instrument</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
