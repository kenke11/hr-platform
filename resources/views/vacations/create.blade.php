@extends('layouts.app')

@section('title', 'Request Vacation')

@section('content')
    <div class="max-w-xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold">
                Request Vacation
            </h1>
            <p class="text-gray-500 text-sm">
                Vacation request for approval
            </p>
        </div>

        {{-- Form --}}
        <form
            method="POST"
            action="{{ route('vacations.store') }}"
            class="bg-white shadow rounded p-6 space-y-4"
        >
            @csrf

            {{-- Start Date --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Start Date
                </label>
                <input
                    type="date"
                    name="start_date"
                    value="{{ old('start_date') }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                @error('start_date')
                <div class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </div>
                @enderror
            </div>

            {{-- End Date --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    End Date
                </label>
                <input
                    type="date"
                    name="end_date"
                    value="{{ old('end_date') }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                @error('end_date')
                <div class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </div>
                @enderror
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Vacation Type
                </label>
                <select
                    name="type"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                    <option value="">Select type</option>
                    <option value="vacation" @selected(old('type') === 'vacation')>
                        Vacation
                    </option>
                    <option value="sick" @selected(old('type') === 'sick')>
                        Sick Vacation
                    </option>
                    <option value="unpaid" @selected(old('type') === 'unpaid')>
                        Unpaid Vacation
                    </option>
                </select>
                @error('type')
                <div class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </div>
                @enderror
            </div>

            {{-- Reason --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Reason (optional)
                </label>
                <textarea
                    name="reason"
                    rows="3"
                    class="w-full border rounded px-3 py-2"
                    placeholder="Optional explanation"
                >{{ old('reason') }}</textarea>
                @error('reason')
                <div class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </div>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    class="bg-black text-white px-4 py-2 rounded"
                >
                    Submit Request
                </button>
            </div>
        </form>
    </div>
@endsection
