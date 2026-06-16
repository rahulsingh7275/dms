@extends('layouts.app')

@section('title', 'Instrument Master')

@section('content')
@php
    $firstItem = method_exists($instruments, 'firstItem') ? $instruments->firstItem() : ($instruments->count() ? 1 : 0);
    $lastItem = method_exists($instruments, 'lastItem') ? $instruments->lastItem() : $instruments->count();
    $totalItems = method_exists($instruments, 'total') ? $instruments->total() : $instruments->count();
@endphp

<div class="mt-3 mb-3">
    <div class="dashbed-border-bottom"></div>
</div>

<div class="row mb-3 align-items-center">
    <div class="col-sm-8">
        <h4>Instrument Master</h4>
        <p>Showing {{ $firstItem }} to {{ $lastItem }} of {{ $totalItems }} instruments</p>
    </div>
    <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
        <a href="{{ route('admin.instruments.create') }}" class="btn-md btn-add"><i class="iconly-boldPlus"></i> Add Instrument</a>
        <a href="#" class="btn-md btn-warning ms-2" data-toggle="modal" data-target="#filterModal"><i class="iconly-boldFilter-2"></i> Filter</a>
        <a href="{{ route('admin.instruments.index') }}" class="btn-md btn-dark ms-2"><i class="fa fa-undo"></i> Clear</a>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade rightModal" id="filterModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-md" role="document" style="max-width:600px;">
        <div class="modal-content bg-light">
            <form method="GET" action="{{ route('admin.instruments.index') }}">
                <div class="modal-header pt-4 pl-4 pr-4 border-0">
                    <div class="pt-2 col-md-12 d-flex align-items-center justify-content-between">
                        <div>
                            <h4>Filter</h4>
                            <h6>Instruments</h6>
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
                            <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Instrument name">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" value="{{ request('code') }}" placeholder="Instrument code">
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



<div class="card p-3">
    <table class="table table-striped table-bordered admintable border-0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th class="text-left">Name</th>
                <th class="text-left">Code</th>
                <th class="text-left">Status</th>
                <th class="text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($instruments as $instrument)
                <tr>
                    <td>{{ $instrument->name }}</td>
                    <td>{{ $instrument->code ?? '-' }}</td>
                    <td>{{ $instrument->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <center>
                            <div class="dropdown">
                                <a class="admintabledrop" data-toggle="dropdown" href="#" aria-expanded="false">
                                    <img src="/assets/admin/img/dot.svg" class="editbtn">
                                </a>
                                <div class="dropdown-menu dropdown-menu-md tableaction dropdown-menu-right">
                                    <ul class="mb-0">
                                        <li><a href="{{ route('admin.instruments.edit', $instrument) }}">Edit</a></li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.instruments.destroy', $instrument) }}" class="m-0" onsubmit="return confirm('Delete this instrument?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item p-0">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </center>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if(method_exists($instruments, 'links'))
        <div class="pagination_rounded pr-4 mt-3">
            {{ $instruments->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
