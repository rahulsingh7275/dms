@extends('layouts.app')

@section('title', 'Metadata Volumes')

@section('content')
    <div class="mt-3 mb-3">
        <div class="dashbed-border-bottom"></div>
    </div>

    <div class="row mb-3 align-items-center">
        <div class="col-sm-8">
            <h4>Metadata Volumes</h4>
            <p>Volumes where all deeds have approved metadata</p>
        </div>
        <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
            <a href="#" class="btn-md btn-warning" data-toggle="modal" data-target="#filterModal"><i class="iconly-boldFilter-2"></i> Filter</a>
            <a href="{{ route('metadata.volumes.index') }}" class="btn-md btn-dark ms-2"><i class="fa fa-undo"></i> Clear</a>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade rightModal" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout modal-lg" role="document" style="max-width: 900px;">
            <div class="modal-content bg-light">
                <form method="GET" action="{{ route('metadata.volumes.index') }}">
                    <div class="modal-header pt-5 pl-5 pr-5 border-0">
                        <div class="pt-3 col-md-12 d-flex align-items-center justify-content-between">
                            <div>
                                <h2>Filter</h2>
                                <h5>Metadata Volumes</h5>
                            </div>
                            <button type="button" class="close search-btn addaddressbtn" data-dismiss="modal" aria-label="Close">
                                <img src="/assets/admin/img/close.svg"/>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body pt-3 pr-5 pl-5">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">District</label>
                                <select name="district_id" id="district_id" class="form-control selectpicker">
                                    <option value="">All districts</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ (string) ($districtId ?? '') === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Office</label>
                                <select name="office_id" id="office_id" class="form-control selectpicker">
                                    <option value="">All offices</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}" data-district="{{ $office->district_id }}" {{ (string) ($officeId ?? '') === (string) $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Volume</label>
                                <input type="text" name="volume" class="form-control" value="{{ $volume ?? '' }}" placeholder="Search volume/year">
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
            @if($indexes->isEmpty())
                <div class="alert alert-info mb-0">No metadata volumes available.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered admintable border-0 align-middle mb-0" cellspacing="0"
                        cellpadding="0">
                        <thead>
                            <tr>
                                <th>State</th>
                                <th>District</th>
                                <th>Office</th>
                                <th>Volume</th>
                                <th>Book</th>
                                <th>Total Deeds</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($indexes as $index)
                                <tr>
                                    <td>{{ optional($index->state)->name ?? '-' }}</td>
                                    <td>{{ optional($index->district)->name ?? '-' }}</td>
                                    <td>{{ optional($index->office)->office_name ?? '-' }}</td>
                                    <td>{{ $index->volume_number ?? '-' }}</td>
                                    <td>{{ $index->book_number ?? '-' }}</td>
                                    <td>{{ $index->deeds->count() }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('metadata.volumes.process', $index) }}"
                                                class="btn-sm btn-secondary mr-2"><i class="fa fa-pencil-square-o px-1"
                                                    aria-hidden="true"></i>Process</a>

                                            @if($index->final_approve)
                                                <button class="btn btn-sm btn-success ml-2" disabled><i
                                                        class="fa fa-check-square-o px-1" aria-hidden="true"></i> Approved</button>
                                            @else
                                                <form method="POST" action="{{ route('metadata.volumes.submit', $index) }}"
                                                    onsubmit="return confirm('Submit this volume?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-share-square-o px-1" aria-hidden="true"></i>Submit</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    <script>
        (function(){
            var districtSelect = document.getElementById('district_id');
            var officeSelect = document.getElementById('office_id');
            if(!districtSelect || !officeSelect) return;

            var originalOptions = Array.from(officeSelect.options).map(function(opt){
                return {
                    value: opt.value,
                    text: opt.text,
                    district: opt.getAttribute('data-district') || ''
                };
            });

            function filterOffices(){
                var selectedDistrict = districtSelect.value;
                // clear
                officeSelect.innerHTML = '';
                // add default
                var defaultOpt = document.createElement('option');
                defaultOpt.value = '';
                defaultOpt.text = 'All offices';
                officeSelect.appendChild(defaultOpt);

                originalOptions.forEach(function(opt){
                    if(opt.value === '') return; // skip default duplicate
                    if(selectedDistrict === '' || opt.district === selectedDistrict){
                        var o = document.createElement('option');
                        o.value = opt.value;
                        o.text = opt.text;
                        o.setAttribute('data-district', opt.district);
                        officeSelect.appendChild(o);
                    }
                });

                // if using bootstrap-select, refresh
                if(window.jQuery && typeof jQuery.fn.selectpicker !== 'undefined'){
                    jQuery(officeSelect).selectpicker('refresh');
                }
            }

            districtSelect.addEventListener('change', filterOffices);
            // run once on load to apply current selection
            filterOffices();
        })();
    </script>
@endsection