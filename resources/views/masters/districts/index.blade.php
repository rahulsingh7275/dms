@extends('layouts.app')

@section('title', 'Districts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>District Master</h3>
    <a href="{{ route('districts.create') }}" class="btn btn-primary">+ Add District</a>
</div>

@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($districts->isEmpty())
    <div class="alert alert-info">
        No districts found. <a href="{{ route('districts.create') }}">Create one</a>
    </div>
@else
<div class="card p-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>District</th>
                <th>State</th>
                <th>Code</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($districts as $district)
                <tr>
                    <td>{{ $district->name }}</td>
                    <td>{{ $district->state->name }}</td>
                    <td>{{ $district->code }}</td>
                    <td>{{ $district->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('districts.edit', $district) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('districts.destroy', $district) }}" class="d-inline-block" onsubmit="return confirm('Delete this district?');">
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
@endif
@endsection
