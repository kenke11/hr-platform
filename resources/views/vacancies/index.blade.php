@extends('layouts.app')

@section('title', 'Vacancies')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Vacancies @if(!empty($company)) - {{$company->name}} @endif</h1>

        @if(auth()->user()->hasRoleInCompany('hr'))
            <a href="{{ route('vacancies.create', !empty($company) ? ['company' => $company->slug] : []) }}"
               class="bg-black text-white px-4 py-2 rounded">
                + Create Vacancy
            </a>
        @endif
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-4 flex flex-wrap items-center gap-3">

        {{-- Status --}}
        <select name="status" class="border rounded px-3 py-2">
            <option value="">All statuses</option>
            <option value="published" @selected(request('status') === 'published')>
                Published
            </option>
            <option value="draft" @selected(request('status') === 'draft')>
                Draft
            </option>
        </select>

        {{-- Expired --}}
        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="expired"
                value="1"
                @checked(request('expired'))
            >
            <span>Expired</span>
        </label>

        {{-- Keep company context (if page opened by slug) --}}
        @if(!empty($company))
            <input type="hidden" name="company" value="{{ $company->slug }}">
        @endif

        <button
            type="submit"
            class="bg-gray-200 px-4 py-2 rounded"
        >
            Filter
        </button>

        {{-- Reset --}}
        @if(request()->anyFilled(['status', 'expired']))
            <a
                href="{{ url()->current() }}"
                class="text-sm text-gray-500"
            >
                Reset
            </a>
        @endif
    </form>

    <div class="bg-white shadow rounded">
        <table class="w-full border-collapse">
            <thead>
            <tr class="border-b bg-gray-50">
                <th class="p-3 text-left">Title</th>

                @if(auth()->user()->canAccessAllCompanies())
                    <th class="p-3 text-left">Company</th>
                @endif

                <th class="p-3 text-left">Type</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Expiration</th>
                <th class="p-3 text-left">Created</th>
                @if(auth()->user()->hasRoleInCompany('hr'))
                    <th class="p-3 text-left">Actions</th>
                @endif
            </tr>
            </thead>

            <tbody>
            @forelse($vacancies as $vacancy)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-medium">
                        {{ $vacancy->title }}
                    </td>

                    @if(auth()->user()->canAccessAllCompanies())
                        <td class="p-3">
                            <a
                                href="{{ route('vacancies.company', $vacancy->company) }}"
                                class="text-blue-600 hover:underline"
                            >
                                {{ $vacancy->company->name }}
                            </a>
                        </td>
                    @endif

                    <td class="p-3">
                        {{ ucfirst(str_replace('_', ' ', $vacancy->employment_type)) }}
                    </td>

                    <td class="p-3">
                        @if($vacancy->isExpired())
                            <span class="text-red-600">Expired</span>
                        @elseif($vacancy->isPublished())
                            <span class="text-green-600">Published</span>
                        @else
                            <span class="text-gray-500">Draft</span>
                        @endif
                    </td>

                    <td class="p-3">
                        {{ $vacancy->expiration_date?->format('Y-m-d') ?? '—' }}
                    </td>

                    <td class="p-3 text-sm text-gray-500">
                        {{ $vacancy->created_at->format('Y-m-d') }}
                    </td>

                    @if(auth()->user()->hasRoleInCompany('hr'))
                        <td class="p-3">
                            <div class="flex gap-2">
                                {{-- Edit --}}
                                <a
                                    href="{{ route('vacancies.edit', $vacancy) }}"
                                    class="text-blue-600 hover:underline text-sm"
                                >
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form
                                    method="POST"
                                    action="{{ route('vacancies.destroy', $vacancy) }}"
                                    onsubmit="return confirm('Delete this vacancy?')"
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
                    @endif
                </tr>

            @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">
                        No vacancies found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $vacancies->links() }}
        </div>
    </div>
@endsection
