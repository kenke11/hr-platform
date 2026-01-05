@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')
    <div class="max-w-xl">

        <h1 class="text-2xl font-bold mb-6">
            Edit Company
        </h1>

        <form
            method="POST"
            action="{{ route('companies.update', $company) }}"
            class="bg-white shadow rounded p-6 space-y-5"
        >
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Company Name
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $company->name) }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                @error('name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Domain --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Company Domain
                </label>
                <input
                    type="text"
                    name="domain"
                    value="{{ old('domain', $company->domain) }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                @error('domain')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug (read-only) --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Slug
                </label>
                <input
                    type="text"
                    value="{{ $company->slug }}"
                    class="w-full border rounded px-3 py-2 bg-gray-100"
                    disabled
                >
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    class="bg-black text-white px-4 py-2 rounded"
                >
                    Update Company
                </button>

                <a
                    href="{{ route('companies.index') }}"
                    class="px-4 py-2 border rounded"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
