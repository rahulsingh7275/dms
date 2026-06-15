@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="card p-4">
    <h3>Edit User</h3>
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-control" required>
                <option value="">Select Role</option>
                @foreach($roles ?? [] as $role)
                    @php
                        $rid = is_object($role) ? ($role->id ?? null) : (is_array($role) ? ($role['id'] ?? null) : null);
                        $rlabel = is_object($role) ? ($role->label ?? $role->name ?? '') : (is_array($role) ? ($role['label'] ?? $role['name'] ?? '') : '');
                    @endphp
                    @if($rid)
                        <option value="{{ $rid }}" {{ old('role_id', $user->role_id) == $rid ? 'selected' : '' }}>
                            {{ $rlabel }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-md btn-primary">Update User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-md btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
