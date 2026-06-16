@extends('layouts.app')

@section('title', 'Instrument Type Master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Instrument Type Master</h3>
    <a href="{{ route('admin.instrument-types.create') }}" class="btn btn-primary">Add Instrument Type</a>
</div>

@if ($message = Session::get('status'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ $message }}
        <button type="button" class="btn-close" data-dismiss="alert"></button>
    </div>
@endif

<div class="card p-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Instrument</th>
                <th>Name</th>
                <th>Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($instrumentTypes as $instrumentType)
                <tr>
                    <td>{{ $instrumentType->instrument->name ?? '-' }}</td>
                    <td>{{ $instrumentType->name }}</td>
                    <td>{{ $instrumentType->code ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.instrument-types.edit', $instrumentType) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('admin.instrument-types.destroy', $instrumentType) }}" class="d-inline-block" onsubmit="return confirm('Delete this instrument type?');">
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
