@if(auth()->user()->canCrudEmployee($company))
    <a href="{{ route('employees.create', $company->slug) }}"
       class="bg-black text-white px-4 py-2 rounded mb-4">
        + Create Employee
    </a>
@endif

<div class="bg-white shadow rounded mt-4">
    <table class="w-full border-collapse">
        <thead>
        <tr class="border-b bg-gray-50">
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3 text-left">Position</th>
            <th class="p-3 text-left">Actions</th>
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

                <td class="p-3">
                    {{ $employee->position->name ?? '-' }}
                </td>

                <td class="p-3 flex gap-2">
                    <a class="text-blue-600 hover:underline text-sm"
                       href="{{ route('employees.show', [$company, $employee]) }}">
                        View
                    </a>

                    <a class="text-blue-600 hover:underline text-sm"
                       href="{{ route('employees.edit', [$company, $employee]) }}">
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
                            class="text-red-600 hover:underline text-sm"
                        >
                            Delete
                        </button>
                    </form>
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
