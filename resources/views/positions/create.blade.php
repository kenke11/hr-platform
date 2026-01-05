@extends('layouts.app')

@section('title', 'Create Position')

@section('content')
    <div class="max-w-md">

        <h1 class="text-2xl font-bold mb-6">
            Create Position — {{ $company->name }}
        </h1>

        <form
            method="POST"
            action="{{ route('positions.store', $company) }}"
            class="bg-white shadow rounded p-6 space-y-4"
        >
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input
                    type="text"
                    name="name"
                    class="w-full border rounded px-3 py-2"
                    required
                >
            </div>


            <div class="mt-4">
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea
                    type="text"
                    name="description"
                    class="w-full border rounded px-3 py-2"
                    required
                ></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button class="bg-black text-white px-4 py-2 rounded">
                    Create
                </button>

                <a href="{{ route('companies.view', $company) }}"
                   class="px-4 py-2 border rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
