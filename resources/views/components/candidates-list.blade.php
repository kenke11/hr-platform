<div class="mt-8">

    <h2 class="text-lg font-semibold mb-4">
        Candidates ({{ $candidates->total() }})
    </h2>

    <div class="bg-white shadow rounded overflow-hidden">

        <table class="w-full border-collapse">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="p-3 text-left text-sm text-gray-600">Name</th>
                <th class="p-3 text-left text-sm text-gray-600">Email</th>
                <th class="p-3 text-left text-sm text-gray-600">Applied</th>
                <th class="p-3 text-left text-sm text-gray-600">Status</th>
                <th class="p-3 text-left text-sm text-gray-600">CV</th>
                <th class="p-3 text-left text-sm text-gray-600">Actions</th>
            </tr>
            </thead>

            <tbody>
            @forelse($candidates as $candidate)
                <tr class="border-b last:border-0 hover:bg-gray-50">
                    <td class="p-3 font-medium">
                        {{ $candidate->full_name }}
                    </td>

                    <td class="p-3">
                        {{ $candidate->email }}
                    </td>

                    <td class="p-3 text-sm text-gray-500">
                        {{ $candidate->created_at->format('Y-m-d') }}
                    </td>

                    <td class="p-3">
                        <span
                            @class([
                                'text-sm px-2 py-1 rounded',
                                'bg-gray-100 text-gray-700' => $candidate->status === 'new',
                                'bg-blue-100 text-blue-700' => $candidate->status === 'reviewed',
                                'bg-green-100 text-green-700' => $candidate->status === 'shortlisted',
                                'bg-red-100 text-red-700' => $candidate->status === 'rejected',
                            ])
                        >
                            {{ ucfirst($candidate->status) }}
                        </span>
                    </td>

                    <td class="p-3">
                        <a
                            href="{{ asset('storage/' . $candidate->cv_path) }}"
                            target="_blank"
                            class="text-blue-600 hover:underline text-sm"
                        >
                            Download
                        </a>
                    </td>
                    <td class="p-3">
                        @if(
                            auth()->check()
                            && auth()->user()->hasRoleInCompany('hr')
                        )
                            <div class="flex gap-2 flex-wrap">

                                {{-- Review --}}
                                @if(in_array($candidate->status, ['new']))
                                    <form
                                        method="POST"
                                        action="{{ route('candidates.status', [$candidate, 'reviewed']) }}"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded"
                                        >
                                            Review
                                        </button>
                                    </form>
                                @endif

                                {{-- Shortlist --}}
                                @if(in_array($candidate->status, ['new', 'reviewed']))
                                    <form
                                        method="POST"
                                        action="{{ route('candidates.status', [$candidate, 'shortlisted']) }}"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded"
                                        >
                                            Shortlist
                                        </button>
                                    </form>
                                @endif

                                {{-- Reject --}}
                                @if($candidate->status !== 'rejected')
                                    <form
                                        method="POST"
                                        action="{{ route('candidates.status', [$candidate, 'rejected']) }}"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded"
                                        >
                                            Reject
                                        </button>
                                    </form>
                                @endif

                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">
                        No candidates yet
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $candidates->links() }}
    </div>
</div>
