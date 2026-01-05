@extends('layouts.app')

@section('title', 'Edit Position')

@section('content')
    <div class="max-w-md">

        <h1 class="text-2xl font-bold mb-6">
            Edit Position — {{ $position->name }}
        </h1>

        <form
            method="POST"
            action="{{ route('positions.update', [$company, $position]) }}"
            class="bg-white shadow rounded p-6 space-y-4"
        >
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $position->name) }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                @error('name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mt-4">
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea
                    name="description"
                    rows="4"
                    class="w-full border rounded px-3 py-2"
                >{{ old('description', $position->description) }}</textarea>

                @error('description')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    class="bg-black text-white px-4 py-2 rounded"
                >
                    Save
                </button>

                <a
                    href="{{ route('positions.show', [$company, $position]) }}"
                    class="px-4 py-2 border rounded"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
