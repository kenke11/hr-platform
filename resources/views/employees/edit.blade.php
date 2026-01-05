@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
    <div class="max-w-xl">

        <h1 class="text-2xl font-bold mb-6">
            Edit Employee — {{ $employee->name }}
        </h1>

        <form
            method="POST"
            action="{{ route('employees.update', [$company, $employee]) }}"
            class="bg-white shadow rounded p-6 space-y-5"
        >
            @csrf
            @method('PUT')

            {{-- =========================
               Name
            ========================= --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $employee->name) }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >

                @error('name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- =========================
               Email
            ========================= --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $employee->email) }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >

                @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- =========================
               Position
            ========================= --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Position
                </label>

                <select
                    name="position_id"
                    class="w-full border rounded px-3 py-2"
                    required
                >
                    @foreach($positions as $position)
                        <option
                            value="{{ $position->id }}"
                            @selected(old('position_id', $employee->position_id) == $position->id)
                        >
                            {{ $position->name }}
                        </option>
                    @endforeach
                </select>

                @error('position_id')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- =========================
               Manager
            ========================= --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Manager (optional)
                </label>

                <select
                    name="manager_id"
                    class="w-full border rounded px-3 py-2"
                >
                    <option value="">— No manager —</option>

                    @foreach($managers as $manager)
                        <option
                            value="{{ $manager->id }}"
                            @selected(old('manager_id', $employee->manager_id) == $manager->id)
                        >
                            {{ $manager->name }}
                        </option>
                    @endforeach
                </select>

                @error('manager_id')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- =========================
               Actions
            ========================= --}}
            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    class="bg-black text-white px-4 py-2 rounded"
                >
                    Save Changes
                </button>

                <a
                    href="{{ route('employees.show', [$company, $employee]) }}"
                    class="px-4 py-2 border rounded"
                >
                    Cancel
                </a>
            </div>

        </form>
    </div>
@endsection
