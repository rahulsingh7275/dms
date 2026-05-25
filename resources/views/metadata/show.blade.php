@extends('layouts.app')

@section('title', 'View Metadata')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Metadata #{{ $metadata->id }} for Deed #{{ $deed->id }}</h3>
        <p class="text-muted">Index: #{{ $deed->index->id ?? '-' }} — {{ $deed->index->state->name ?? '-' }} / {{ $deed->index->district->name ?? '-' }} / {{ $deed->index->office->office_name ?? '-' }}</p>
    </div>
    <a href="{{ route('metadata.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card p-4 mb-4">
    <dl class="row">
        <dt class="col-sm-3">Presentation Year</dt>
        <dd class="col-sm-9">{{ $metadata->presentation_year }}</dd>

        <dt class="col-sm-3">Deed Number</dt>
        <dd class="col-sm-9">{{ $metadata->deed_number }}</dd>

        <dt class="col-sm-3">Party Name</dt>
        <dd class="col-sm-9">{{ $metadata->party_name }}</dd>

        <dt class="col-sm-3">Property Details</dt>
        <dd class="col-sm-9">{{ $metadata->property_details }}</dd>

        <dt class="col-sm-3">Village</dt>
        <dd class="col-sm-9">{{ $metadata->village }}</dd>

        <dt class="col-sm-3">Area</dt>
        <dd class="col-sm-9">{{ $metadata->area }}</dd>

        <dt class="col-sm-3">Registration Date</dt>
        <dd class="col-sm-9">{{ optional($metadata->registration_date)->format('Y-m-d') ?? '-' }}</dd>

        <dt class="col-sm-3">Status</dt>
        <dd class="col-sm-9">{{ ucfirst($metadata->status) }}</dd>
    </dl>
</div>

@php $user = auth()->user(); @endphp
<div class="card p-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
        @if($user && ($user->isOperator() || $user->isAdmin()) && $metadata->status !== 'approved')
            <a href="{{ route('deeds.metadata.edit', [$deed, $metadata]) }}" class="btn btn-primary">Edit Metadata</a>
            <form method="POST" action="{{ route('deeds.metadata.destroy', [$deed, $metadata]) }}" class="d-inline-block" onsubmit="return confirm('Delete this metadata?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete Metadata</button>
            </form>
        @else
            <span class="badge bg-secondary">Read-only view</span>
        @endif
    </div>
</div>
@endsection