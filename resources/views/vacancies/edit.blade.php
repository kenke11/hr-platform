@extends('layouts.app')

@section('title', 'Edit Vacancy')

@section('content')
    <div class="max-w-2xl">

        <h1 class="text-2xl font-bold mb-6">
            Edit Vacancy
        </h1>

        <form
            method="POST"
            action="{{ route('vacancies.update', $vacancy) }}"
            class="bg-white shadow rounded p-6 space-y-5"
        >
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium mb-1">Title</label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $vacancy->title) }}"
                    class="w-full border rounded px-3 py-2"
                >
                @error('title') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea
                    name="description"
                    rows="5"
                    class="w-full border rounded px-3 py-2"
                >{{ old('description', $vacancy->description) }}</textarea>
                @error('description') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Location --}}
            <div>
                <label class="block text-sm font-medium mb-1">Location</label>
                <input
                    type="text"
                    name="location"
                    value="{{ old('location', $vacancy->location) }}"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            {{-- Employment Type --}}
            <div>
                <label class="block text-sm font-medium mb-1">Employment Type</label>
                <select name="employment_type" class="w-full border rounded px-3 py-2">
                    @foreach(['full_time','part_time','contract'] as $type)
                        <option
                            value="{{ $type }}"
                            @selected(old('employment_type', $vacancy->employment_type) === $type)
                        >
                            {{ ucfirst(str_replace('_',' ',$type)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Expiration --}}
            <div>
                <label class="block text-sm font-medium mb-1">Expiration Date</label>
                <input
                    type="date"
                    name="expiration_date"
                    value="{{ old('expiration_date', optional($vacancy->expiration_date)->format('Y-m-d')) }}"
                    class="w-full border rounded px-3 py-2"
                >
                @error('expiration_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
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
                    Update & Publish
                </button>

                @if($vacancy->status === 'draft')
                {{-- Save as draft --}}
                <button
                    type="submit"
                    name="action"
                    value="draft"
                    class="bg-gray-200 text-gray-800 px-4 py-2 rounded"
                >
                    Save as Draft
                </button>
                @endif

                <a
                    href="{{ url()->previous() }}"
                    class="px-4 py-2 border rounded"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
