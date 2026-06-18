@extends('layouts.app')

@section('title', 'All Deeds')

@section('content')
@php
    $firstItem = method_exists($deeds, 'firstItem') ? $deeds->firstItem() : ($deeds->count() ? 1 : 0);
    $lastItem = method_exists($deeds, 'lastItem') ? $deeds->lastItem() : $deeds->count();
    $totalItems = method_exists($deeds, 'total') ? $deeds->total() : $deeds->count();
@endphp

<div class="mt-3 mb-3">
    <div class="dashbed-border-bottom"></div>
</div>

<div class="row mb-3 align-items-center">
    <div class="col-sm-8">
        <h4>All Deeds</h4>
        <p>Showing {{ $firstItem }} to {{ $lastItem }} of {{ $totalItems }} deeds</p>
    </div>
    <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
        @php $user = auth()->user(); @endphp
        @if(isset($index) && $index && $user && $user->isOperator())
            <a href="{{ route('indexes.deeds.create', $index) }}" class="btn-md btn-add"><i class="iconly-boldPlus"></i> Add Deeds</a>
        @endif
        <a href="#" class="btn-md btn-warning" data-toggle="modal" data-target="#filterModal"><i class="iconly-boldFilter-2"></i> Filter</a>
        <a href="{{ route('deeds.index') }}" class="btn-md btn-dark ms-2"><i class="fa fa-undo"></i> Clear</a>
    </div>
</div>
    <!-- Filter modal trigger placed above; modal defined below -->

    <!-- Filter Modal -->
    <div class="modal fade rightModal" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout modal-lg" role="document" style="max-width: 900px;">
            <div class="modal-content bg-light">
                <form method="GET" action="{{ route('deeds.index') }}">
                    <div class="modal-header pt-5 pl-5 pr-5 border-0">
                        <div class="pt-3 col-md-12 d-flex align-items-center justify-content-between">
                            <div>
                                <h2>Filter</h2>
                                <h5>Deeds</h5>
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
                            <div class="col-md-4">
                                <label class="form-label">Presentation Year</label>
                                <input type="text" name="presentation_year" class="form-control" value="{{ $presentationYear }}" placeholder="Filter deed year">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Deed Number</label>
                                <input type="text" name="deed_number" class="form-control" value="{{ $deedNumber }}" placeholder="Filter deed number">
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
                                <input type="date" name="registration_date" class="form-control" value="{{ $registrationDate }}">
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
    @if($deeds->isEmpty())
        <div class="alert alert-info mb-0">
            No deeds match the selected filters.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered admintable border-0 align-middle mb-0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>State</th>
                        <th>District</th>
                        <th>Office</th>
                        <th>Volume Year</th>
                        <th>Book</th>
                        <th>Volume</th>
                        <th>Presentation Year</th>
                        <th>Deed Number</th>
                        <th>Party Name</th>
                        <th>Village</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deeds as $deed)
                        @php $index = $deed->index; @endphp
                        <tr>
                            <td>{{ optional($index?->state)->name ?? '-' }}</td>
                            <td>{{ optional($index?->district)->name ?? '-' }}</td>
                            <td>{{ optional($index?->office)->office_name ?? '-' }}</td>
                            <td>{{ $index?->volume_year ?? '-' }}</td>
                            <td>{{ $index?->book_number ?? '-' }}</td>
                            <td>{{ $index?->volume_number ?? '-' }}</td>
                            <td>{{ $deed->presentation_year ?? '-' }}</td>
                            <td>{{ $deed->deed_number ?? '-' }}</td>
                            <td>{{ $deed->party_name ?? '-' }}</td>
                            <td>{{ $deed->village ?? '-' }}</td>
                            <td>{{ ucfirst($deed->status) }}</td>
                            <td>
                                <center>
                                    @php $user = auth()->user(); @endphp
                                    @if(!$index)
                                        <span class="badge bg-secondary">No index linked</span>
                                    @else
                                        <div class="dropdown">
                                            <a class="admintabledrop" data-toggle="dropdown" href="#" aria-expanded="false">
                                                <img src="/assets/admin/img/dot.svg" class="editbtn">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-md tableaction dropdown-menu-right">
                                                <ul class="mb-0">
                                                    @if($deed->scannedDocuments->isNotEmpty() && !$user->isDepartmentHead())
                                                        @php $doc = $deed->scannedDocuments->last(); @endphp
                                                        <li><a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">View PDF</a></li>
                                                        <li><a href="{{ route('deeds.download', $deed) }}">Download</a></li>
                                                    @endif

                                                    @if($user && $user->isOperator() && $deed->status == 'approved' && $deed->scannedDocuments->isEmpty())
                                                        <li>
                                                            <a href="#" data-toggle="modal" data-target="#scannedCopyModal" data-deed-id="{{ $deed->id }}" data-deed-number="{{ $deed->deed_number }}">Upload Scanned Copy</a>
                                                        </li>
                                                    @endif

                                                    @if($user && $user->isChecker())
                                                        <li><a href="{{ route('indexes.deeds.show', [$index, $deed]) }}">View</a></li>
                                                    @elseif($deed->status === 'approved' && !$user->isDepartmentHead())
                                                        <li><a href="{{ route('indexes.deeds.show', [$index, $deed]) }}">View</a></li>
                                                    @elseif($deed->status !== 'approved' && $user && ($user->isOperator() || $user->isAdmin()))
                                                        <li><a href="{{ route('indexes.deeds.edit', [$index, $deed]) }}">Edit</a></li>
                                                        <li>
                                                            <form method="POST" action="{{ route('indexes.deeds.destroy', [$index, $deed]) }}" class="m-0" onsubmit="return confirm('Delete this deed?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item p-0">Delete</button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    @if($deed->metadata)
                                                        @if($user && $user->isChecker())
                                                            <li><a href="{{ route('deeds.metadata.show', [$deed, $deed->metadata]) }}">View Metadata</a></li>
                                                        @elseif($deed->metadata->status === 'approved')
                                                            <li><a href="{{ route('deeds.metadata.show', [$deed, $deed->metadata]) }}">View Metadata</a></li>
                                                        @else
                                                            <li><a href="{{ route('deeds.metadata.edit', [$deed, $deed->metadata]) }}">Edit Metadata</a></li>
                                                        @endif
                                                    @else
                                                        @if($user && ($user->isOperator() || $user->isAdmin()) && $deed->status == 'approved' && $deed->scannedDocuments->isNotEmpty())
                                                            <li><a href="{{ route('deeds.metadata.create', $deed) }}">Create Metadata</a></li>
                                                        @endif
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

<!-- Upload scanned copy modal -->
<div class="modal fade" id="scannedCopyModal" tabindex="-1" aria-labelledby="scannedCopyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scannedCopyModalLabel">Upload Scanned Copy</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="scannedCopyForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p id="scannedCopyDeedLabel" class="fw-semibold"></p>
                    <div class="mb-3">
                        <label class="form-label">Scanned Copy (PDF)</label>
                        <input type="file" name="scanned_copy" class="form-control" accept="application/pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var scannedCopyModal = document.getElementById('scannedCopyModal');
        if (!scannedCopyModal) {
            return;
        }

        $('#scannedCopyModal').on('show.bs.modal', function (event) {
            debugger;
            var button = $(event.relatedTarget);
            // console.log('Button that triggered the modal:', button);
            // console.log('Button that triggered the modal:', button[0].dataset.deedId);
            var deedId = button[0].dataset.deedId;
            var deedNumber = button[0].dataset.deedNumber;
            // var deedId = button.getAttribute('data-deed-id');
            // var deedNumber = button.getAttribute('data-deed-number');
            var form = document.getElementById('scannedCopyForm');
            // console.log('Form element:', form); 
            var label = document.getElementById('scannedCopyDeedLabel');

            form.action = '/deeds/' + deedId + '/scanned-copy';
            label.textContent = 'Upload scanned PDF for deed #' + deedNumber;
        });
    });
 
</script>
@endsection
