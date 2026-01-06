@extends('layouts.app')

@section('title', $employee->name)

@section('content')
    <div class="w-full">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">
                {{ $employee->name }}
            </h1>

            <div class="flex gap-3">
                <a
                    href="{{ route('employees.edit', [$company, $employee]) }}"
                    class="px-4 py-2 border rounded text-sm"
                >
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
                        class="px-4 py-2 text-red-600 border rounded text-sm"
                    >
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Info --}}
        <div class="bg-white shadow rounded p-6 space-y-3">
            <div>
                <span class="text-gray-500 text-sm">Email</span>
                <div class="font-medium">
                    {{ $employee->email }}
                </div>
            </div>

            <div>
                <span class="text-gray-500 text-sm">Company</span>
                <div class="font-medium">
                    {{ $company->name }}
                </div>
            </div>

            <div>
                <span class="text-gray-500 text-sm">Position</span>
                <div class="font-medium">
                    {{ $employee->position?->name ?? '—' }}
                </div>
            </div>

            <div>
                <span class="text-gray-500 text-sm">Manager</span>
                <div class="font-medium">
                    {{ $employee->manager?->name ?? '—' }}
                </div>
            </div>

            <div>
                <span class="text-gray-500 text-sm">Joined</span>
                <div class="font-medium">
                    {{ $employee->created_at->format('Y-m-d') }}
                </div>
            </div>
        </div>

        {{-- Back --}}
        <div class="pt-6">
            <a
                href="{{ route('companies.view', $company) }}"
                class="text-sm text-gray-500 hover:underline"
            >
                ← Back to company
            </a>
        </div>

        {{-- Absence action --}}
        @if(
            auth()->user()->hasRoleInCompany('hr')
            && ! $todayAttendance
        )
            <form
                method="POST"
                action="{{ route('attendance.absence', [$company, $employee]) }}"
                class="mb-4 mt-4 space-y-3"
            >
                @csrf

                {{-- Reason --}}
                <div>
                    <label class="block text-sm text-gray-600 mb-1">
                        Absence reason (optional)
                    </label>

                    <textarea
                        name="reason"
                        rows="3"
                        class="w-full border rounded px-3 py-2 text-sm"
                        placeholder="Sick, vacation, personal..."
                    >{{ old('reason') }}</textarea>

                    @error('reason')
                    <div class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="bg-yellow-500 text-white px-4 py-2 rounded"
                >
                    Mark as Absent (Today)
                </button>
            </form>
        @endif


        {{-- Subordinates --}}
        @if($subordinates->count())
            <div class="mt-8">
                <h2 class="text-lg font-semibold mb-3">
                    Subordinates
                </h2>

                <div class="bg-white shadow rounded overflow-hidden">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="p-3 text-left text-sm text-gray-600">Name</th>
                            <th class="p-3 text-left text-sm text-gray-600">Position</th>
                            <th class="p-3 text-left text-sm text-gray-600">Email</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($subordinates as $subordinate)
                            <tr class="border-b last:border-0">
                                <td class="p-3 font-medium">
                                    <a
                                        href="{{ route('employees.show', [$company, $subordinate]) }}"
                                        class="text-blue-600 hover:underline"
                                    >
                                        {{ $subordinate->name }}
                                    </a>
                                </td>

                                <td class="p-3">
                                    {{ $subordinate->position?->name ?? '—' }}
                                </td>

                                <td class="p-3">
                                    {{ $subordinate->email }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $subordinates->links() }}
                </div>
            </div>
        @endif

        {{-- Attendance --}}
        <div class="mt-8">
            <h2 class="text-lg font-semibold mb-3">
                Attendance
            </h2>

            <div class="bg-white shadow rounded overflow-hidden">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-3 text-left text-sm text-gray-600">Date</th>
                        <th class="p-3 text-left text-sm text-gray-600">Check In</th>
                        <th class="p-3 text-left text-sm text-gray-600">Check Out</th>
                        <th class="p-3 text-left text-sm text-gray-600">Status</th>
                        <th class="p-3 text-left text-sm text-gray-600">Reason</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($attendances as $attendance)
                        <tr class="border-b last:border-0">
                            <td class="p-3">
                                {{ $attendance->date->format('Y-m-d') }}
                            </td>

                            <td class="p-3">
                                {{ $attendance->check_in_at?->format('H:i') ?? '—' }}
                            </td>

                            <td class="p-3">
                                {{ $attendance->check_out_at?->format('H:i') ?? '—' }}
                            </td>

                            <td class="p-3">
                                @if($attendance->is_absent)
                                    <span class="text-red-600 text-sm font-medium">
                                Absent
                            </span>
                                @elseif($attendance->check_in_at && $attendance->check_out_at)
                                    <span class="text-green-600 text-sm">
                                Completed
                            </span>
                                @elseif($attendance->check_in_at)
                                    <span class="text-yellow-600 text-sm">
                                Checked in
                            </span>
                                @else
                                    <span class="text-gray-400 text-sm">
                                —
                            </span>
                                @endif
                            </td>

                            <td class="p-3 text-sm text-gray-600">
                                {{ $attendance->absence_reason ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                No attendance records
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        </div>

    </div>
@endsection
