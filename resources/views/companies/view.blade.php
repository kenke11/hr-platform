@extends('layouts.app')

@section('title', $company->name)

@section('content')
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="bg-white shadow rounded p-6 mb-6">
            <h1 class="text-3xl font-bold mb-2">
                {{ $company->name }}
            </h1>

            <div class="text-gray-600 space-y-1">
                <p>
                    <strong>Domain:</strong>
                    {{ $company->domain ?? '—' }}
                </p>
                <p>
                    <strong>Slug:</strong>
                    {{ $company->slug }}
                </p>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="border-b mb-6">
            <nav class="flex gap-6">

                {{-- Vacancies --}}
                <a
                    href="{{ route('companies.view', $company) }}?tab=vacancies"
                    @class([
                        'pb-2 font-medium',
                        'border-b-2 border-black' => $tab === 'vacancies',
                        'text-gray-500' => $tab !== 'vacancies',
                    ])
                >
                    Vacancies
                </a>

                {{-- Employees --}}
                <a
                    href="{{ route('companies.view', $company) }}?tab=employees"
                    @class([
                        'pb-2 font-medium',
                        'border-b-2 border-black' => $tab === 'employees',
                        'text-gray-500' => $tab !== 'employees',
                    ])
                >
                    Employees
                </a>

            </nav>
        </div>

        {{-- Tab Content --}}
        @if($tab === 'vacancies')
            @include('companies.partials.vacancies')
        @endif

        @if($tab === 'employees')
            @include('companies.partials.employees')
        @endif
    </div>
@endsection
