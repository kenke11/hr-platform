@extends('layouts.app')

@section('title', 'Companies')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Companies</h1>

        @if(auth()->user()->hasRoleInCompany('hr') || auth()->user()->hasRoleInCompany('admin'))
            <a href="{{ route('companies.create') }}"
               class="bg-black text-white px-4 py-2 rounded">
                + Create Company
            </a>
        @endif
    </div>

    <div class="bg-white shadow rounded">
        <table class="w-full border-collapse">
            <thead>
            <tr class="border-b bg-gray-50">
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Slug</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Created</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($companies as $company)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $company->id }}</td>
                    <td class="p-3 font-medium">{{ $company->name }}</td>
                    <td class="p-3 text-gray-600">{{ $company->slug }}</td>
                    <td class="p-3">
                        @if($company->is_active)
                            <span class="text-green-600">Active</span>
                        @else
                            <span class="text-red-600">Inactive</span>
                        @endif
                    </td>
                    <td class="p-3 text-sm text-gray-500">
                        {{ $company->created_at->format('Y-m-d') }}
                    </td>
                    <td class="p-3">
                        <div class="flex gap-2">
                            {{-- View --}}
                            <a
                                href="{{ route('companies.view', $company->slug) }}"
                                class="text-blue-600 hover:underline text-sm"
                            >
                                View
                            </a>

                            {{-- Edit --}}
                            <a
                                href="{{ route('companies.edit', $company->slug) }}"
                                class="text-blue-600 hover:underline text-sm"
                            >
                                Edit
                            </a>

                            {{-- Delete --}}
                            <form
                                method="POST"
                                action="{{ route('companies.destroy', $company->slug) }}"
                                onsubmit="return confirm('Delete this company?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-red-600 hover:underline text-sm"
                                >
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">
                        No companies found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $companies->links() }}
        </div>
    </div>
@endsection
