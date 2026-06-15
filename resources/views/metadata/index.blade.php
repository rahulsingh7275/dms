@extends('layouts.app')

@section('title', 'Metadata List')

@section('content')
@php
    $firstItem = method_exists($metadataList, 'firstItem') ? $metadataList->firstItem() : ($metadataList->count() ? 1 : 0);
    $lastItem = method_exists($metadataList, 'lastItem') ? $metadataList->lastItem() : $metadataList->count();
    $totalItems = method_exists($metadataList, 'total') ? $metadataList->total() : $metadataList->count();
@endphp

<div class="mt-3 mb-3">
    <div class="dashbed-border-bottom"></div>
</div>

<div class="row mb-3 align-items-center">
    <div class="col-sm-8">
        <h4>Metadata List</h4>
        <p>Showing {{ $firstItem }} to {{ $lastItem }} of {{ $totalItems }} entries</p>
    </div>
    <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
        <a href="#" class="btn-md btn-warning" data-toggle="modal" data-target="#filterModal"><i class="iconly-boldFilter-2"></i> Filter</a>
        <a href="{{ route('metadata.index') }}" class="btn-md btn-dark ms-2"><i class="fa fa-undo"></i> Clear</a>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade rightModal" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-lg" role="document" style="max-width: 900px;">
        <div class="modal-content bg-light">
            <form method="GET" action="{{ route('metadata.index') }}">
                <div class="modal-header pt-5 pl-5 pr-5 border-0">
                    <div class="pt-3 col-md-12 d-flex align-items-center justify-content-between">
                        <div>
                            <h2>Filter</h2>
                            <h5>Metadata</h5>
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
                            <select name="state_id" id="state_id" class="form-control selectpicker">
                                <option value="">All states</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ (string) $stateId === (string) $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">District</label>
                            <select name="district_id" id="district_id" class="form-control selectpicker">
                                <option value="">All districts</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ (string) $districtId === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Office</label>
                            <select name="office_id" id="office_id" class="form-control selectpicker">
                                <option value="">All offices</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}" {{ (string) $officeId === (string) $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-control selectpicker">
                                <option value="">All statuses</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Volume</label>
                            <input type="text" name="volume" class="form-control" value="{{ $volume }}" placeholder="Search volume/year">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Book</label>
                            <input type="text" name="book" class="form-control" value="{{ $book }}" placeholder="Filter book">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Deed Number</label>
                            <input type="text" name="deed_number" class="form-control" value="{{ $deedNumber }}" placeholder="Filter deed number">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Presentation Year</label>
                            <input type="text" name="presentation_year" class="form-control" value="{{ $presentationYear }}" placeholder="Filter year">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Party Name</label>
                            <input type="text" name="party_name" class="form-control" value="{{ $partyName }}" placeholder="Filter party">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Village</label>
                            <input type="text" name="village" class="form-control" value="{{ $village }}" placeholder="Filter village">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Registration Date</label>
                            <input type="date" name="date" class="form-control" value="{{ $date }}">
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

<div class="card p-3">
    @if($metadataList->isEmpty())
        <div class="alert alert-info mb-0">
            No metadata matches the selected filters.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered admintable border-0 align-middle mb-0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>State</th>
                        <th>District</th>
                        <th>Office</th>
                        <th>Volume</th>
                        <th>Book</th>
                        <th>Deed Number</th>
                        <th>Presentation Year</th>
                        <th>Party Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($metadataList as $metadata)
                        @php
                            $deed = $metadata->deed;
                            $index = $deed?->index;
                        @endphp
                        <tr>
                            <td>{{ optional($index?->state)->name ?? '-' }}</td>
                            <td>{{ optional($index?->district)->name ?? '-' }}</td>
                            <td>{{ optional($index?->office)->office_name ?? '-' }}</td>
                            <td>{{ $index?->volume_number ?? '-' }}</td>
                            <td>{{ $index?->book_number ?? '-' }}</td>
                            <td>{{ $metadata->deed_number ?? '-' }}</td>
                            <td>{{ $metadata->presentation_year ?? '-' }}</td>
                            <td>{{ $metadata->party_name ?? '-' }}</td>
                            <td>{{ ucfirst($metadata->status) }}</td>
                            <td>
                                <center>
                                    @if(!$deed)
                                        <span class="badge bg-secondary">No deed linked</span>
                                    @else
                                        <div class="dropdown">
                                            <a class="admintabledrop" data-toggle="dropdown" href="#" aria-expanded="false">
                                                <img src="/assets/admin/img/dot.svg" class="editbtn">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-md tableaction dropdown-menu-right">
                                                <ul class="mb-0">
                                                    @php $user = auth()->user(); @endphp
                                                    @if($metadata->status === 'approved' || ($user && $user->isChecker()))
                                                        <li><a href="{{ route('deeds.metadata.show', [$deed, $metadata]) }}">View</a></li>
                                                    @else
                                                        <li><a href="{{ route('deeds.metadata.edit', [$deed, $metadata]) }}">Edit</a></li>
                                                        <li>
                                                            <form method="POST" action="{{ route('deeds.metadata.destroy', [$deed, $metadata]) }}" class="m-0" onsubmit="return confirm('Delete this metadata?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item p-0">Delete</button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    @if($index)
                                                        <li><a href="{{ route('indexes.deeds.index', $index) }}">View Deed</a></li>
                                                    @else
                                                        <li><span class="text-muted">No volume linked</span></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </center>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
