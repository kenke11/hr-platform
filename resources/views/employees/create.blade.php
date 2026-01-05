@extends('layouts.app')

@section('title', 'Create Employee')

@section('content')
    <div class="max-w-xl">

        <h1 class="text-2xl font-bold mb-6">
            Create Employee
            @if(!empty($company))
                — {{ $company->name }}
            @endif
        </h1>

        <form
            method="POST"
            action="{{ route('employees.store', $company->slug) }}"
            class="bg-white shadow rounded p-6 space-y-5"
        >
            @csrf

            {{-- ============================
               Company
            ============================= --}}
            <input type="hidden" name="company_id" value="{{ $company->id }}">

            {{-- ============================
               Name
            ============================= --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >

                @error('name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- ============================
               Email
            ============================= --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border rounded px-3 py-2"
                    required
                >

                @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- ============================
               Position
            ============================= --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Position
                </label>

                <select
                    name="position_id"
                    class="w-full border rounded px-3 py-2"
                    required
                    @disabled(empty($positions))
                >
                    <option value="">— Select position —</option>

                    @foreach($positions as $position)
                        <option
                            value="{{ $position->id }}"
                            @selected(old('position_id') == $position->id)
                        >
                            {{ $position->name }}
                        </option>
                    @endforeach
                </select>

                @error('position_id')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror

                @if($positions->isEmpty())
                    <p class="text-sm text-gray-500 mt-1">
                        Select company first
                    </p>
                @endif
            </div>

            {{-- ============================
               Manager
            ============================= --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Manager (optional)
                </label>

                <select
                    name="manager_id"
                    class="w-full border rounded px-3 py-2"
                    @disabled(empty($managers))
                >
                    <option value="">— No manager —</option>

                    @foreach($managers as $manager)
                        <option
                            value="{{ $manager->id }}"
                            @selected(old('manager_id') == $manager->id)
                        >
                            {{ $manager->name }}
                        </option>
                    @endforeach
                </select>

                @error('manager_id')
                <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- ============================
               Actions
            ============================= --}}
            <div class="flex gap-3 pt-4">
                <button
                    type="submit"
                    class="bg-black text-white px-4 py-2 rounded"
                >
                    Create Employee
                </button>

                <a
                    href="{{ !empty($company)
                    ? route('companies.view', $company)
                    : route('dashboard')
                }}"
                    class="px-4 py-2 border rounded"
                >
                    Cancel
                </a>
            </div>

        </form>
    </div>
@endsection
