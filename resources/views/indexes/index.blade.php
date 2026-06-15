@extends('layouts.app')

@section('title', 'Indexes')

@section('content')
<section class="content mb-3">
    <div class="container-fluid">
        @php
            $user = auth()->user();
            $firstItem = method_exists($indexes, 'firstItem') ? $indexes->firstItem() : ($indexes->count() ? 1 : 0);
            $lastItem = method_exists($indexes, 'lastItem') ? $indexes->lastItem() : $indexes->count();
            $totalItems = method_exists($indexes, 'total') ? $indexes->total() : $indexes->count();
        @endphp

        <div class="mt-3 mb-3">
            <div class="dashbed-border-bottom"></div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-sm-8">
                <h4>Indexes</h4>
                <p>Showing {{ $firstItem }} to {{ $lastItem }} of {{ $totalItems }} indexes</p>
            </div>
            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                @if($user && !$user->isChecker())
                    <a href="{{ route('indexes.create') }}" class="btn-md btn-add"><i class="iconly-boldPlus"></i> Add Index</a>
                @endif
                <a href="#" class="btn-md btn-warning" data-toggle="modal" data-target="#filterModal"><i class="iconly-boldFilter-2"></i> Filter</a>
                {{-- <a href="#" class="btn-md btn-dark"><i class="iconly-boldSearch"></i> Search</a> --}}
                {{-- <a href="{{ route('indexes.index') }}" class="btn-md btn-dark"><i class="fa fa-undo"></i> Reset</a> --}}
                {{-- <a href="javascript:void(0);" class="text-dark" onclick="event.preventDefault();"><img src="/assets/admin/img/export.svg"> Export Indexes</a> --}}
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-light border rounded p-3">
                    <p class="mb-0">Use the Filter button to open advanced index search options in a popup.</p>
                </div>
            </div>
        </div>

        <div class="modal fade rightModal" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-slideout modal-lg" role="document" style="max-width: 900px;">
                <div class="modal-content bg-light">
                    <form method="GET" action="{{ route('indexes.index') }}">
                        <div class="modal-header pt-5 pl-5 pr-5 border-0">
                            <div class="pt-3 col-md-12 d-flex align-items-center justify-content-between">
                                <div>
                                    <h2>Filter</h2>
                                    <h5>Indexes</h5>
                                </div>
                                <button type="button" class="close search-btn addaddressbtn" data-dismiss="modal" aria-label="Close">
                                    <img src="/assets/admin/img/close.svg"/>
                                </button>
                            </div>
                        </div>
                        <div class="modal-body pt-3 pr-5 pl-5">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">State</label>
                                    <select name="state_id" class="form-control selectpicker">
                                        <option value="">All states</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ (string) $stateId === (string) $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">District</label>
                                    <select name="district_id" class="form-control selectpicker">
                                        <option value="">All districts</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ (string) $districtId === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Office</label>
                                    <select name="office_id" class="form-control selectpicker">
                                        <option value="">All offices</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}" {{ (string) $officeId === (string) $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control selectpicker">
                                        <option value="">All statuses</option>
                                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Volume Year</label>
                                    <input type="text" name="volume_year" class="form-control" value="{{ $volumeYear }}" placeholder="Filter year">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Book</label>
                                    <input type="text" name="book_number" class="form-control" value="{{ $bookNumber }}" placeholder="Filter book">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Volume</label>
                                    <input type="text" name="volume_number" class="form-control" value="{{ $volumeNumber }}" placeholder="Filter volume">
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-secondary btn-radius">Apply Filters</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <div class="table-responsive">
                    @if($indexes->isEmpty())
                        <div class="alert alert-info mb-0">
                            No indexes match the selected filters.
                        </div>
                    @else
                        <table class="table table-striped table-bordered admintable border-0" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <th class="text-left">State</th>
                                    <th class="text-left">District</th>
                                    <th class="text-left">Office</th>
                                    <th class="text-left">Volume Year</th>
                                    <th class="text-left">Book</th>
                                    <th class="text-left">Volume</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($indexes as $index)
                                    <tr>
                                        <td>{{ $index->state->name }}</td>
                                        <td>{{ $index->district->name }}</td>
                                        <td>{{ $index->office->office_name }}</td>
                                        <td>{{ $index->volume_year }}</td>
                                        <td>{{ $index->book_number }}</td>
                                        <td>{{ $index->volume_number }}</td>
                                        <td>{{ ucfirst($index->status) }}</td>
                                        <td>
                                            <center>
                                                <div class="dropdown">
                                                    <a class="admintabledrop" data-toggle="dropdown" href="#" aria-expanded="false">
                                                        <img src="/assets/admin/img/dot.svg" class="editbtn">
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-md tableaction dropdown-menu-right">
                                                        <ul class="mb-0">
                                                            @if($user && ($user->isOperator() || $user->isAdmin()))
                                                                @if($index->status !== 'approved')
                                                                    <li><a href="{{ route('indexes.edit', $index) }}">Edit</a></li>
                                                                    <li>
                                                                        <form method="POST" action="{{ route('indexes.destroy', $index) }}" class="m-0" onsubmit="return confirm('Delete this index?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item p-0">Delete</button>
                                                                        </form>
                                                                    </li>
                                                                @else
                                                                    <li><a href="{{ route('indexes.deeds.index', $index) }}">Deeds</a></li>
                                                                @endif
                                                            @elseif($user && $user->isChecker())
                                                                <li><a href="{{ route('indexes.show', $index) }}">View</a></li>
                                                            @else
                                                                <li><a href="{{ route('indexes.edit', $index) }}">Edit</a></li>
                                                                @if($index->status === 'approved')
                                                                    <li><a href="{{ route('indexes.deeds.index', $index) }}">Deeds</a></li>
                                                                @endif
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
                        @if(method_exists($indexes, 'links'))
                            <div class="pagination_rounded pr-4 mt-3">
                                {{ $indexes->withQueryString()->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </section>
        </div>
    </div>
</section>
@endsection
