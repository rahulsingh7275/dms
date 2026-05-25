@extends('layouts.app')

@section('title', 'View Deed')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Deed #{{ $deed->id }} Details</h3>
        <p class="text-muted">Index: #{{ $index->id }} — {{ $index->state->name ?? '-' }} / {{ $index->district->name ?? '-' }} / {{ $index->office->office_name ?? '-' }}</p>
    </div>
    <a href="{{ route('indexes.deeds.index', $index) }}" class="btn btn-secondary">Back</a>
</div>

<div class="card p-4 mb-4">
    <dl class="row">
        <dt class="col-sm-3">Presentation Year</dt>
        <dd class="col-sm-9">{{ $deed->presentation_year }}</dd>

        <dt class="col-sm-3">Deed Number</dt>
        <dd class="col-sm-9">{{ $deed->deed_number }}</dd>

        <dt class="col-sm-3">Party Name</dt>
        <dd class="col-sm-9">{{ $deed->party_name }}</dd>

        <dt class="col-sm-3">Property Details</dt>
        <dd class="col-sm-9">{{ $deed->property_details }}</dd>

        <dt class="col-sm-3">Village</dt>
        <dd class="col-sm-9">{{ $deed->village }}</dd>

        <dt class="col-sm-3">Area</dt>
        <dd class="col-sm-9">{{ $deed->area }}</dd>

        <dt class="col-sm-3">Registration Date</dt>
        <dd class="col-sm-9">{{ optional($deed->registration_date)->format('Y-m-d') ?? '-' }}</dd>

        <dt class="col-sm-3">Status</dt>
        <dd class="col-sm-9">{{ ucfirst($deed->status) }}</dd>

        <dt class="col-sm-3">Scanned Document</dt>
        <dd class="col-sm-9">
            @if($deed->scannedDocuments->isNotEmpty())
                @php $doc = $deed->scannedDocuments->last(); @endphp
                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">View PDF</a>
                <a href="{{ route('deeds.download', $deed) }}" class="btn btn-sm btn-outline-success">Download</a>
            @else
                -
            @endif
        </dd>

        <dt class="col-sm-3">Metadata</dt>
        <dd class="col-sm-9">
            @if($deed->metadata)
                {{ ucfirst($deed->metadata->status) }}
            @else
                No metadata yet
            @endif
        </dd>
    </dl>
</div>

@php $user = auth()->user(); @endphp
<div class="card p-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
        @if($user && ($user->isOperator() || $user->isAdmin()))
            <a href="{{ route('indexes.deeds.edit', [$index, $deed]) }}" class="btn btn-secondary">Edit Deed</a>
            <form method="POST" action="{{ route('indexes.deeds.destroy', [$index, $deed]) }}" class="d-inline-block" onsubmit="return confirm('Delete this deed?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete Deed</button>
            </form>
        @else
            <span class="badge bg-secondary">Read-only view</span>
        @endif

        @if($deed->metadata)
            <a href="{{ route('deeds.metadata.show', [$deed, $deed->metadata]) }}" class="btn btn-info">View Metadata</a>
        @elseif($user && ($user->isOperator() || $user->isAdmin()))
            <a href="{{ route('deeds.metadata.create', $deed) }}" class="btn btn-info">Create Metadata</a>
        @endif
    </div>
</div>
@endsection