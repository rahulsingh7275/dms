@extends('layouts.app')

@section('title', 'Districts')

@section('content')
<section class="content mb-3">
    <div class="container-fluid">
        @php
            $firstItem = method_exists($districts, 'firstItem') ? $districts->firstItem() : ($districts->count() ? 1 : 0);
            $lastItem = method_exists($districts, 'lastItem') ? $districts->lastItem() : $districts->count();
            $totalItems = method_exists($districts, 'total') ? $districts->total() : $districts->count();
        @endphp

        <div class="mt-3 mb-3">
            <div class="dashbed-border-bottom"></div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-8">
                <h4>Districts</h4>
                <p>Showing {{ $firstItem }} to {{ $lastItem }} of {{ $totalItems }} districts</p>
            </div>
            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                <a href="{{ route('districts.create') }}" class="btn-md btn-add"><i class="iconly-boldPlus"></i> Add District</a>
                <a href="#" class="btn-md btn-warning" data-toggle="modal" data-target="#filterModal"><i class="iconly-boldFilter-2"></i> Filter</a>
            </div>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('status') }}
                <button type="button" class="btn-close" data-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="table-responsive">
                    @if($districts->isEmpty())
                        <div class="alert alert-info mb-0">
                            No districts found. <a href="{{ route('districts.create') }}">Create one</a>
                        </div>
                    @else
                        <table class="table table-striped table-bordered admintable border-0" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <th class="text-left">District</th>
                                    <th class="text-left">State</th>
                                    <th class="text-left">Code</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-left">Action</th>
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
                                            <center>
                                                <div class="dropdown">
                                                    <a class="admintabledrop" data-toggle="dropdown" href="#" aria-expanded="false">
                                                        <img src="/assets/admin/img/dot.svg" class="editbtn">
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-md tableaction dropdown-menu-right">
                                                        <ul class="mb-0">
                                                            <li><a href="{{ route('districts.edit', $district) }}">Edit</a></li>
                                                            <li>
                                                                <form method="POST" action="{{ route('districts.destroy', $district) }}" class="m-0" onsubmit="return confirm('Delete this district?');">
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
                        @if(method_exists($districts, 'links'))
                            <div class="pagination_rounded pr-4 mt-3">
                                {{ $districts->withQueryString()->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </section>
        </div>
    </div>
</section>
@endsection
