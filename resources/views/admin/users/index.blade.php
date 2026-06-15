@extends('layouts.app')

@section('title', 'User Management')

@section('content')
@php
    $firstItem = method_exists($users, 'firstItem') ? $users->firstItem() : ($users->count() ? 1 : 0);
    $lastItem = method_exists($users, 'lastItem') ? $users->lastItem() : $users->count();
    $totalItems = method_exists($users, 'total') ? $users->total() : $users->count();
@endphp

<div class="mt-3 mb-3">
    <div class="dashbed-border-bottom"></div>
</div>

<div class="row mb-3 align-items-center">
    <div class="col-sm-8">
        <h4>User Management</h4>
        <p>Showing {{ $firstItem }} to {{ $lastItem }} of {{ $totalItems }} users</p>
    </div>
    <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
        <a href="{{ route('admin.users.create') }}" class="btn-md btn-add"><i class="iconly-boldPlus"></i> Create User</a>
        <a href="#" class="btn-md btn-warning ms-2" data-toggle="modal" data-target="#filterModal"><i class="iconly-boldFilter-2"></i> Filter</a>
        <a href="{{ route('admin.users.index') }}" class="btn-md btn-dark ms-2"><i class="fa fa-undo"></i> Clear</a>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade rightModal" id="filterModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-md" role="document" style="max-width:600px;">
        <div class="modal-content bg-light">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="modal-header pt-4 pl-4 pr-4 border-0">
                    <div class="pt-2 col-md-12 d-flex align-items-center justify-content-between">
                        <div>
                            <h4>Filter</h4>
                            <h6>Users</h6>
                        </div>
                        <button type="button" class="close search-btn addaddressbtn" data-dismiss="modal" aria-label="Close">
                            <img src="/assets/admin/img/close.svg"/>
                        </button>
                    </div>
                </div>
                <div class="modal-body pt-3 pr-4 pl-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="User name">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ request('email') }}" placeholder="Email">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Role</label>
                            <select name="role_id" class="form-control selectpicker">
                                <option value="">Any role</option>
                                @foreach($roles ?? [] as $role)
                                    @php
                                        $roleId = is_object($role) ? ($role->id ?? null) : (is_array($role) ? ($role['id'] ?? null) : null);
                                        $roleLabel = is_object($role) ? ($role->label ?? $role->name ?? '') : (is_array($role) ? ($role['label'] ?? $role['name'] ?? '') : '');
                                    @endphp
                                    @if($roleId)
                                        <option value="{{ $roleId }}" {{ request('role_id') == $roleId ? 'selected' : '' }}>{{ $roleLabel }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <button type="submit" class="btn btn-secondary btn-radius">Apply Filters</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($message = Session::get('status'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card p-3">
    <table class="table table-striped table-bordered admintable border-0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role?->label ?? 'User' }}</td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    <td>
                        <center>
                            <div class="dropdown">
                                <a class="admintabledrop" data-toggle="dropdown" href="#" aria-expanded="false">
                                    <img src="/assets/admin/img/dot.svg" class="editbtn">
                                </a>
                                <div class="dropdown-menu dropdown-menu-md tableaction dropdown-menu-right">
                                    <ul class="mb-0">
                                        <li><a href="{{ route('admin.users.edit', $user) }}">Edit</a></li>
                                        @if ($user->id !== auth()->id())
                                            <li>
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="m-0" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item p-0">Delete</button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </center>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

