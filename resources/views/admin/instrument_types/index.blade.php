@extends('layouts.app')

@section('title', 'Instrument Type Master')

@section('content')
<section class="content mb-3">
    <div class="container-fluid">
        @php
            $firstItem = method_exists($instrumentTypes, 'firstItem') ? $instrumentTypes->firstItem() : ($instrumentTypes->count() ? 1 : 0);
            $lastItem = method_exists($instrumentTypes, 'lastItem') ? $instrumentTypes->lastItem() : $instrumentTypes->count();
            $totalItems = method_exists($instrumentTypes, 'total') ? $instrumentTypes->total() : $instrumentTypes->count();
        @endphp

        <div class="mt-3 mb-3">
            <div class="dashbed-border-bottom"></div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-8">
                <h4>Instrument Types</h4>
                <p>Showing {{ $firstItem }} to {{ $lastItem }} of {{ $totalItems }} instrument types</p>
            </div>
            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                <a href="{{ route('admin.instrument-types.create') }}" class="btn-md btn-add"><i class="iconly-boldPlus"></i> Add Instrument Type</a>
                <a href="#" class="btn-md btn-warning" data-toggle="modal" data-target="#filterModal"><i class="iconly-boldFilter-2"></i> Filter</a>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="table-responsive">
                    @if($instrumentTypes->isEmpty())
                        <div class="alert alert-info mb-0">
                            No instrument types available.
                        </div>
                    @else
                        <table class="table table-striped table-bordered admintable border-0" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <th class="text-left">Instrument</th>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Code</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($instrumentTypes as $instrumentType)
                                    <tr>
                                        <td>{{ $instrumentType->instrument->name ?? '-' }}</td>
                                        <td>{{ $instrumentType->name }}</td>
                                        <td>{{ $instrumentType->code ?? '-' }}</td>
                                        <td>{{ $instrumentType->status ? 'Active' : 'Inactive' }}</td>
                                        <td>
                                            <center>
                                                <div class="dropdown">
                                                    <a class="admintabledrop" data-toggle="dropdown" href="#" aria-expanded="false">
                                                        <img src="/assets/admin/img/dot.svg" class="editbtn">
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-md tableaction dropdown-menu-right">
                                                        <ul class="mb-0">
                                                            <li><a href="{{ route('admin.instrument-types.edit', $instrumentType) }}">Edit</a></li>
                                                            <li>
                                                                <form method="POST" action="{{ route('admin.instrument-types.destroy', $instrumentType) }}" class="m-0" onsubmit="return confirm('Delete this instrument type?');">
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
                        @if(method_exists($instrumentTypes, 'links'))
                            <div class="pagination_rounded pr-4 mt-3">
                                {{ $instrumentTypes->withQueryString()->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </section>
        </div>
    </div>
</section>
@endsection
