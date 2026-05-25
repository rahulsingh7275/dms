@extends('layouts.app')

@section('title', 'States')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>State Master</h3>
    <a href="{{ route('states.create') }}" class="btn btn-primary">Add State</a>
</div>
<div class="card p-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($states as $state)
                <tr>
                    <td>{{ $state->name }}</td>
                    <td>{{ $state->code }}</td>
                    <td>{{ $state->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('states.edit', $state) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('states.destroy', $state) }}" class="d-inline-block" onsubmit="return confirm('Delete this state?');">
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
