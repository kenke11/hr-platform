@if(auth()->user()->canCrudPositions($company))
    <a href="{{ route('positions.create', $company->slug) }}"
       class="bg-black text-white px-4 py-2 rounded mb-4">
        + Create Position
    </a>
@endif

<div class="bg-white shadow rounded mt-4">

    <table class="w-full border-collapse">
        <thead>
        <tr class="border-b bg-gray-50">
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Description</th>
            @if(auth()->user()->canCrudPositions($company))
                <th class="p-3 text-left">Actions</th>
            @endif
        </tr>
        </thead>

        <tbody>
        @forelse($positions as $position)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 font-medium">
                    {{ $position->name }}
                </td>

                <td class="p-3">
                    {{ $position->description ?? '-' }}
                </td>

                @if(auth()->user()->canCrudPositions($company))
                    <td class="p-3 flex gap-2">
                        <a class="text-blue-600 hover:underline text-sm"
                           href="{{ route('positions.edit', [$company, $position]) }}">
                            Edit
                        </a>

                        <form
                            method="POST"
                            action="{{ route('positions.destroy', [$company, $position]) }}"
                            onsubmit="return confirm('Delete this position?')"
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
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="3" class="p-4 text-center text-gray-500">
                    No positions found
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="p-4">
        {{ $positions->links() }}
    </div>
</div>
