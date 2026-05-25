@extends('layouts.app')

@section('title', 'Metadata Verification')

@section('content')
<div class="mb-4">
    <h3>Metadata Verification</h3>
</div>
<div class="card p-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Deed</th>
                <th>Presentation Year</th>
                <th>Party</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metadataList as $metadata)
                <tr>
                    <td>{{ $metadata->deed->deed_number }}</td>
                    <td>{{ $metadata->presentation_year }}</td>
                    <td>{{ $metadata->party_name }}</td>
                    <td>{{ ucfirst($metadata->status) }}</td>
                    <td>
                        <form method="POST" action="{{ route('verifications.metadata.verify', $metadata) }}" class="d-flex gap-2">
                            @csrf
                            <select name="status" class="form-select form-select-sm" required>
                                <option value="approved">Approve</option>
                                <option value="rejected">Reject</option>
                            </select>
                            <button class="btn btn-sm btn-success">Save</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
