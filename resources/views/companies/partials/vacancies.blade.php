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
            @if(auth()->user()->hasRoleInCompany('hr') || auth()->user()->hasRoleInCompany('admin'))
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

                @if(auth()->user()->hasRoleInCompany('hr') || auth()->user()->hasRoleInCompany('admin'))
                    <td class="p-3">
                        <div class="flex gap-2">
                            {{-- View --}}
                            <a
                                href="{{ route('vacancies.view', $vacancy) }}"
                                class="text-blue-600 hover:underline text-sm"
                            >
                                View
                            </a>

                            @if(auth()->user()->hasRoleInCompany('hr'))
                                {{-- Edit --}}
                                <a
                                    href="{{ route('vacancies.edit', $vacancy) }}"
                                    class="text-blue-600 hover:underline text-sm"
                                >
                                    Edit
                                </a>
                            @endif

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
