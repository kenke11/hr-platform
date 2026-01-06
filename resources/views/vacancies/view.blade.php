@extends('layouts.app')

@section('title', $vacancy->title)

@section('content')
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2">
                {{ $vacancy->title }}
            </h1>

            <p class="text-gray-600">
                {{ $vacancy->company->name }}
                • {{ ucfirst(str_replace('_', ' ', $vacancy->employment_type)) }}
                @if($vacancy->location)
                    • {{ $vacancy->location }}
                @endif
            </p>
        </div>

        {{-- Status (HR only) --}}
        @if(auth()->check() && auth()->user()->hasRoleInCompany('hr'))
            <div class="mb-4">
                @if($vacancy->isExpired())
                    <span class="text-red-600 text-sm">Expired</span>
                @elseif($vacancy->isPublished())
                    <span class="text-green-600 text-sm">Published</span>
                @else
                    <span class="text-gray-500 text-sm">Draft</span>
                @endif
            </div>
        @endif

        {{-- Description --}}
        <div class="bg-white shadow rounded p-6 mb-6">
            <h2 class="text-lg font-semibold mb-2">Job Description</h2>
            <div class="prose max-w-none">
                {!! nl2br(e($vacancy->description)) !!}
            </div>
        </div>

        {{-- Meta --}}
        <div class="text-sm text-gray-500">
            <p>Created at: {{ $vacancy->created_at->format('Y-m-d') }}</p>

            @if($vacancy->published_at)
                <p>Published at: {{ $vacancy->published_at->format('Y-m-d') }}</p>
            @endif

            @if($vacancy->expiration_date)
                <p>Expires at: {{ $vacancy->expiration_date->format('Y-m-d') }}</p>
            @endif
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex gap-3">
            @if(auth()->check() && auth()->user()->hasRoleInCompany('hr'))
                <a
                    href="{{ route('vacancies.edit', $vacancy) }}"
                    class="bg-black text-white px-4 py-2 rounded"
                >
                    Edit
                </a>
            @endif
        </div>

        <x-candidates-list :candidates="$candidates" />
    </div>
@endsection
