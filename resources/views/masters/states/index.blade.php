@extends('layouts.app')

@section('title', 'States')

@section('content')
<section class="content mb-3">
    <div class="container-fluid">
        @php
            $firstItem = method_exists($states, 'firstItem') ? $states->firstItem() : ($states->count() ? 1 : 0);
            $lastItem = method_exists($states, 'lastItem') ? $states->lastItem() : $states->count();
            $totalItems = method_exists($states, 'total') ? $states->total() : $states->count();
        @endphp

        <div class="mt-3 mb-3">
            <div class="dashbed-border-bottom"></div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-8">
                <h4>States</h4>
                <p>Showing {{ $firstItem }} to {{ $lastItem }} of {{ $totalItems }} states</p>
            </div>
            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                <a href="{{ route('states.create') }}" class="btn-md btn-add"><i class="iconly-boldPlus"></i> Add State</a>
                <a href="#" class="btn-md btn-warning" data-toggle="modal" data-target="#filterModal"><i class="iconly-boldFilter-2"></i> Filter</a>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="table-responsive">
                    @if($states->isEmpty())
                        <div class="alert alert-info mb-0">
                            No states available.
                        </div>
                    @else
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
                                @foreach($states as $state)
                                    <tr>
                                        <td>{{ $state->name }}</td>
                                        <td>{{ $state->code }}</td>
                                        <td>{{ $state->status ? 'Active' : 'Inactive' }}</td>
                                        <td>
                                            <center>
                                                <div class="dropdown">
                                                    <a class="admintabledrop" data-toggle="dropdown" href="#" aria-expanded="false">
                                                        <img src="/assets/admin/img/dot.svg" class="editbtn">
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-md tableaction dropdown-menu-right">
                                                        <ul class="mb-0">
                                                            <li><a href="{{ route('states.edit', $state) }}">Edit</a></li>
                                                            <li>
                                                                <form method="POST" action="{{ route('states.destroy', $state) }}" class="m-0" onsubmit="return confirm('Delete this state?');">
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
                        @if(method_exists($states, 'links'))
                            <div class="pagination_rounded pr-4 mt-3">
                                {{ $states->withQueryString()->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </section>
        </div>
    </div>
</section>
@endsection
