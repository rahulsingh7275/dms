@extends('layouts.app')

@section('title', 'Instrument Master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Instrument Master</h3>
    <a href="{{ route('admin.instruments.create') }}" class="btn btn-primary">Add Instrument</a>
</div>

@if ($message = Session::get('status'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card p-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($instruments as $instrument)
                <tr>
                    <td>{{ $instrument->name }}</td>
                    <td>{{ $instrument->code ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.instruments.edit', $instrument) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('admin.instruments.destroy', $instrument) }}" class="d-inline-block" onsubmit="return confirm('Delete this instrument?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
