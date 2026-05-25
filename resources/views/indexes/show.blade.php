@extends('layouts.app')

@section('title', 'View Index')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Index #{{ $index->id }} Details</h3>
        <p class="text-muted">{{ $index->state->name ?? '-' }} / {{ $index->district->name ?? '-' }} / {{ $index->office->office_name ?? '-' }}</p>
    </div>
    <a href="{{ route('indexes.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card p-3 mb-4">
    <dl class="row">
        <dt class="col-sm-3">Volume Year</dt>
        <dd class="col-sm-9">{{ $index->volume_year }}</dd>

        <dt class="col-sm-3">Book Number</dt>
        <dd class="col-sm-9">{{ $index->book_number }}</dd>

        <dt class="col-sm-3">Volume Number</dt>
        <dd class="col-sm-9">{{ $index->volume_number }}</dd>

        <dt class="col-sm-3">Status</dt>
        <dd class="col-sm-9">{{ ucfirst($index->status) }}</dd>
        @php $latestVerification = $index->indexVerifications->sortByDesc('verified_at')->first(); @endphp
        <dt class="col-sm-3">Latest Remark</dt>
        <dd class="col-sm-9">{{ $latestVerification->remarks ?? '-' }}</dd>
        <dt class="col-sm-3">Locked</dt>
        <dd class="col-sm-9">{{ $index->locked ? 'Yes' : 'No' }}</dd>
        <dt class="col-sm-3">Created By</dt>
        <dd class="col-sm-9">{{ $index->creator?->name ?? $index->created_by ?? '-' }}</dd>
        <dt class="col-sm-3">Created At</dt>
        <dd class="col-sm-9">{{ $index->created_at }}</dd>
        <dt class="col-sm-3">Updated At</dt>
        <dd class="col-sm-9">{{ $index->updated_at }}</dd>
    </dl>
</div>

@php $user = auth()->user(); @endphp
@if($user && $user->isChecker() && $index->status === 'pending')
    <div class="card p-3 mb-4">
        <h5>Change Status (Checker only)</h5>
        <form method="POST" action="{{ route('indexes.status.update', $index) }}">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" id="status-select" class="form-select">
                        <option value="pending" {{ $index->status === 'pending' ? 'selected' : '' }}>Under Process</option>
                        <option value="approved" {{ $index->status === 'approved' ? 'selected' : '' }}>Approve</option>
                        <option value="rejected" {{ $index->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                    </select>
                </div>
                <div class="col-md-6" id="comment-box" style="display: {{ $index->status === 'rejected' ? 'block' : 'none' }};">
                    <label class="form-label">Comment (required when rejecting)</label>
                    <textarea name="comment" class="form-control">{{ old('comment', $latestVerification->remarks ?? '') }}</textarea>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Save</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('status-select').addEventListener('change', function(e) {
            var commentBox = document.getElementById('comment-box');
            if (e.target.value === 'rejected') {
                commentBox.style.display = 'block';
            } else {
                commentBox.style.display = 'none';
            }
        });
    </script>
@endif

<div class="card p-3">
    <h5>Deeds in this Index</h5>
    @if($index->deeds->isEmpty())
        <div class="alert alert-info">No deeds available.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Presentation Year</th>
                    <th>Deed Number</th>
                    <th>Party Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($index->deeds as $deed)
                    <tr>
                        <td>{{ $deed->presentation_year }}</td>
                        <td>{{ $deed->deed_number }}</td>
                        <td>{{ $deed->party_name }}</td>
                        <td>{{ ucfirst($deed->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
