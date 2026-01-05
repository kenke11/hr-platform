@extends('layouts.app')

@section('title', 'Create Vacancy')

@section('content')
    <div class="max-w-2xl">

        <h1 class="text-2xl font-bold mb-2">
            Create Vacancy
        </h1>

        <form
            method="POST"
            action="{{ route('vacancies.store') }}"
            class="bg-white shadow rounded p-6 space-y-5"
        >
            @csrf

            {{-- Company --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Company <span class="text-red-600">*</span>
                </label>

                <select
                    name="company_id"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                    <option value="">Select company</option>

                    @foreach($companies as $company)
                        <option
                            value="{{ $company->id }}"
                            @selected(
                                old('company_id') == $company->id
                                || (isset($selectedCompany) && $selectedCompany?->id === $company->id)
                            )
                        >
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>

                @error('company_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Title <span class="text-red-600">*</span>
                </label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Description <span class="text-red-600">*</span>
                </label>
                <textarea
                    name="description"
                    rows="5"
                    class="w-full border rounded px-3 py-2"
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Location --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Location
                </label>
                <input
                    type="text"
                    name="location"
                    value="{{ old('location') }}"
                    class="w-full border rounded px-3 py-2"
                >
                @error('location')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Employment Type --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Employment Type <span class="text-red-600">*</span>
                </label>
                <select
                    name="employment_type"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                    <option value="">Select type</option>
                    <option value="full_time" @selected(old('employment_type') === 'full_time')>
                        Full-time
                    </option>
                    <option value="part_time" @selected(old('employment_type') === 'part_time')>
                        Part-time
                    </option>
                    <option value="contract" @selected(old('employment_type') === 'contract')>
                        Contract
                    </option>
                </select>
                @error('employment_type')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Expiration Date --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Expiration Date
                </label>
                <input
                    type="date"
                    name="expiration_date"
                    value="{{ old('expiration_date') }}"
                    class="w-full border rounded px-3 py-2"
                >
                @error('expiration_date')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-4">

                {{-- Publish --}}
                <button
                    type="submit"
                    name="action"
                    value="published"
                    class="bg-black text-white px-4 py-2 rounded"
                >
                    Create & Publish
                </button>

                {{-- Save as draft --}}
                <button
                    type="submit"
                    name="action"
                    value="draft"
                    class="bg-gray-200 text-gray-800 px-4 py-2 rounded"
                >
                    Save as Draft
                </button>

                {{-- Cancel --}}
                <a
                    href="{{ route('vacancies.index') }}"
                    class="px-4 py-2 border rounded"
                >
                    Cancel
                </a>
            </div>

        </form>
    </div>
@endsection
