@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

    <div class="bg-white rounded shadow p-4">
        <p class="mb-2">
            Welcome,
            <strong>{{ auth()->user()->name }}</strong>
        </p>

        @if(auth()->user()->canAccessAllCompanies())
            <p class="text-sm text-gray-600">
                You are logged in as a system user (admin / HR).
            </p>
        @else
            <p class="text-sm text-gray-600">
                Company:
                <strong>{{ auth()->user()->company?->name }}</strong>
            </p>
        @endif
    </div>
@endsection
