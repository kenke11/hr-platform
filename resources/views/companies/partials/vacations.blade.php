 {{-- Table --}}
<div class="bg-white shadow rounded overflow-hidden">
    <table class="w-full border-collapse">
        <thead class="bg-gray-50 border-b">
        <tr>
            <th class="p-3 text-left text-sm text-gray-600">Employee</th>
            <th class="p-3 text-left text-sm text-gray-600">Period</th>
            <th class="p-3 text-left text-sm text-gray-600">Type</th>
            <th class="p-3 text-left text-sm text-gray-600">Status</th>
            <th class="p-3 text-left text-sm text-gray-600">Submitted by</th>
            <th class="p-3 text-left text-sm text-gray-600">Approved At</th>
            <th class="p-3 text-left text-sm text-gray-600">Actions</th>
        </tr>
        </thead>

        <tbody>
        @forelse($vacations as $vacation)
            <tr class="border-b last:border-0">
                <td class="p-3 font-medium">
                    {{ $vacation->user->name }}
                    <div class="text-xs text-gray-500">
                        {{ $vacation->user->email }}
                    </div>
                </td>

                <td class="p-3">
                    {{ $vacation->start_date->format('Y-m-d') }}
                    →
                    {{ $vacation->end_date->format('Y-m-d') }}
                </td>

                <td class="p-3 capitalize">
                    {{ $vacation->type }}
                </td>

                <td class="p-3">
                    @if($vacation->status === 'pending')
                        <span class="text-yellow-600 text-sm">Pending</span>
                    @elseif($vacation->status === 'approved')
                        <span class="text-green-600 text-sm">Approved</span>
                    @else
                        <span class="text-red-600 text-sm">Rejected</span>
                    @endif
                </td>

                <td class="p-3 text-sm text-gray-500">
                    {{ $vacation->approver->name ?? '—' }}
                </td>

                <td class="p-3 text-sm text-gray-500">
                    {{ $vacation->created_at->format('Y-m-d') }}
                </td>

                @if(auth()->user()->canApproveOrRejectVacation($company))
                    <td class="p-3">
                        @can('approve', $vacation)
                            <div class="flex gap-2">
                                <form method="POST"
                                      action="{{ route('vacations.approve', $vacation) }}">
                                    @csrf
                                    <button class="text-green-600 text-sm">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('vacations.reject', $vacation) }}">
                                    @csrf
                                    <button class="text-red-600 text-sm">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">—</span>
                        @endcan
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="6" class="p-4 text-center text-gray-500">
                    No vacation requests found
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-4">
    {{ $vacations->links() }}
</div>
