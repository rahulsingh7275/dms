@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <h2>Welcome, {{ $user->name }}</h2>
            <p class="text-muted">Role: {{ $user->role?->label ?? 'User' }}</p>
            <div class="row mt-4 g-3">
                @foreach($summary as $label => $value)
                    <div class="col-md-3">
                        <div class="card border-0 bg-light p-3 text-center">
                            <h5 class="mb-1 text-capitalize">{{ $label }}</h5>
                            <p class="display-6 mb-0">{{ $value }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
