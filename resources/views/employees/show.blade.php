@extends('layouts.app')

@section('title', $employee->name)

@section('content')
    <div class="max-w-xl">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">
                {{ $employee->name }}
            </h1>

            <div class="flex gap-3">
                <a
                    href="{{ route('employees.edit', [$company, $employee]) }}"
                    class="px-4 py-2 border rounded text-sm"
                >
                    Edit
                </a>

                <form
                    method="POST"
                    action="{{ route('employees.destroy', [$company, $employee]) }}"
                    onsubmit="return confirm('Delete this employee?')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="px-4 py-2 text-red-600 border rounded text-sm"
                    >
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Info --}}
        <div class="bg-white shadow rounded p-6 space-y-3">
            <div>
                <span class="text-gray-500 text-sm">Email</span>
                <div class="font-medium">
                    {{ $employee->email }}
                </div>
            </div>

            <div>
                <span class="text-gray-500 text-sm">Company</span>
                <div class="font-medium">
                    {{ $company->name }}
                </div>
            </div>

            <div>
                <span class="text-gray-500 text-sm">Position</span>
                <div class="font-medium">
                    {{ $employee->position?->name ?? '—' }}
                </div>
            </div>

            <div>
                <span class="text-gray-500 text-sm">Manager</span>
                <div class="font-medium">
                    {{ $employee->manager?->name ?? '—' }}
                </div>
            </div>

            <div>
                <span class="text-gray-500 text-sm">Joined</span>
                <div class="font-medium">
                    {{ $employee->created_at->format('Y-m-d') }}
                </div>
            </div>
        </div>

        {{-- Back --}}
        <div class="pt-6">
            <a
                href="{{ route('companies.view', $company) }}"
                class="text-sm text-gray-500 hover:underline"
            >
                ← Back to company
            </a>
        </div>

    </div>
@endsection
