<div class="bg-white shadow rounded">

    <table class="w-full border-collapse">
        <thead>
        <tr class="border-b bg-gray-50">
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Email</th>
        </tr>
        </thead>

        <tbody>
        @forelse($employees as $employee)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 font-medium">
                    {{ $employee->name }}
                </td>

                <td class="p-3">
                    {{ $employee->email }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="p-4 text-center text-gray-500">
                    No employees found
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="p-4">
        {{ $employees->links() }}
    </div>
</div>
