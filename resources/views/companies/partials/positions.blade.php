<div class="bg-white shadow rounded">

    <table class="w-full border-collapse">
        <thead>
        <tr class="border-b bg-gray-50">
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Description</th>
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
