@extends('layouts.app')

@section('title', 'Registration Offices')

@section('content')
<section class="content mb-3">
    <div class="container-fluid">
        @php
            $firstItem = method_exists($offices, 'firstItem') ? $offices->firstItem() : ($offices->count() ? 1 : 0);
            $lastItem = method_exists($offices, 'lastItem') ? $offices->lastItem() : $offices->count();
            $totalItems = method_exists($offices, 'total') ? $offices->total() : $offices->count();
        @endphp

        <div class="mt-3 mb-3">
            <div class="dashbed-border-bottom"></div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-8">
                <h4>Vault Registration Offices</h4>
                <p>Showing {{ $firstItem }} to {{ $lastItem }} of {{ $totalItems }} offices</p>
            </div>
            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                <a href="{{ route('offices.create') }}" class="btn-md btn-add"><i class="iconly-boldPlus"></i> Add Office</a>
                <a href="#" class="btn-md btn-warning" data-toggle="modal" data-target="#filterModal"><i class="iconly-boldFilter-2"></i> Filter</a>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="table-responsive">
                    @if($offices->isEmpty())
                        <div class="alert alert-info mb-0">
                            No offices found. <a href="{{ route('offices.create') }}">Create one</a>
                        </div>
                    @else
                        <table class="table table-striped table-bordered admintable border-0" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <th class="text-left">Office Name</th>
                                    <th class="text-left">District</th>
                                    <th class="text-left">State</th>
                                    <th class="text-left">Code</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-left">Action</th>
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
                                            <center>
                                                <div class="dropdown">
                                                    <a class="admintabledrop" data-toggle="dropdown" href="#" aria-expanded="false">
                                                        <img src="/assets/admin/img/dot.svg" class="editbtn">
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-md tableaction dropdown-menu-right">
                                                        <ul class="mb-0">
                                                            <li><a href="{{ route('offices.edit', $office) }}">Edit</a></li>
                                                            <li>
                                                                <form method="POST" action="{{ route('offices.destroy', $office) }}" class="m-0" onsubmit="return confirm('Delete this office?');">
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
                        @if(method_exists($offices, 'links'))
                            <div class="pagination_rounded pr-4 mt-3">
                                {{ $offices->withQueryString()->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </section>
        </div>
    </div>
</section>
@endsection
