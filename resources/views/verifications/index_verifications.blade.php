@extends('layouts.app')

@section('title', 'Index Verification')

@section('content')
<div class="mb-4">
    <h3>Index Verification</h3>
</div>
<div class="card p-3">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Index</th>
                <th>Office</th>
                <th>Volume</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($indexes as $index)
                <tr>
                    <td>{{ $index->id }}</td>
                    <td>{{ $index->office->office_name }}</td>
                    <td>{{ $index->volume_year }}/{{ $index->book_number }}/{{ $index->volume_number }}</td>
                    <td>{{ ucfirst($index->status) }}</td>
                    <td>
                        <form method="POST" action="{{ route('verifications.index.verify', $index) }}" class="d-flex gap-2">
                            @csrf
                            <select name="status" class="form-control form-control-sm" required>
                                <option value="approved">Approve</option>
                                <option value="rejected">Reject</option>
                                <option value="sent_back">Send Back</option>
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
