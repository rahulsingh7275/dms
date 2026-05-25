@extends('layouts.app')

@section('title', 'Registration Offices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Vault Registration Office Master</h3>
    <a href="{{ route('offices.create') }}" class="btn btn-primary">Add Office</a>
</div>
<div class="card p-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Office Name</th>
                <th>District</th>
                <th>State</th>
                <th>Code</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($offices as $office)
                <tr>
                    <td>{{ $office->office_name }}</td>
                    <td>{{ $office->district->name }}</td>
                    <td>{{ $office->district->state->name }}</td>
                    <td>{{ $office->office_code }}</td>
                    <td>{{ $office->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('offices.edit', $office) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('offices.destroy', $office) }}" class="d-inline-block" onsubmit="return confirm('Delete this office?');">
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
