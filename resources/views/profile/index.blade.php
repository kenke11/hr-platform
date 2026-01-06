@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="max-w-full">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold">
                {{ $user->name }}
            </h1>

            <p class="text-gray-500">
                {{ $user->email }}
            </p>
        </div>

        @if(auth()->user()->isCompanyUser())
            {{-- Check-in / Check-out --}}
            <div class="mb-6 flex gap-3">
                @if($todayAttendance)
                    @if(! $todayAttendance->check_in_at)
                        {{-- Check In --}}
                        <form method="POST" action="{{ route('attendance.checkin') }}">
                            @csrf
                            <button
                                type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded"
                            >
                                Check In
                            </button>
                        </form>

                    @elseif(! $todayAttendance->check_out_at)
                        {{-- Check Out --}}
                        <form method="POST" action="{{ route('attendance.checkout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded"
                            >
                                Check Out
                            </button>
                        </form>
                    @endif
                @else
                    {{-- No attendance yet — Check In --}}
                    <form method="POST" action="{{ route('attendance.checkin') }}">
                        @csrf
                        <button
                            type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded"
                        >
                            Check In
                        </button>
                    </form>
                @endif
            </div>
        @endif

        {{-- Info --}}
        <div class="bg-white shadow rounded p-6 grid grid-cols-2 gap-4 mb-8">
            <div>
                <span class="text-sm text-gray-500">Company</span>
                <div class="font-medium">
                    {{ $user->company?->name ?? '—' }}
                </div>
            </div>

            <div>
                <span class="text-sm text-gray-500">Position</span>
                <div class="font-medium">
                    {{ $user->position?->name ?? '—' }}
                </div>
            </div>

            <div>
                <span class="text-sm text-gray-500">Manager</span>
                <div class="font-medium">
                    {{ $user->manager?->name ?? '—' }}
                </div>
            </div>

            <div>
                <span class="text-sm text-gray-500">Joined</span>
                <div class="font-medium">
                    {{ $user->created_at->format('Y-m-d') }}
                </div>
            </div>
        </div>

        @if(auth()->user()->isCompanyUser())
            {{-- Attendance --}}
            <div class="mb-10">
                <h2 class="text-lg font-semibold mb-3">
                    Attendance
                </h2>

                <div class="bg-white shadow rounded overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="p-3 text-left text-sm">Date</th>
                            <th class="p-3 text-left text-sm">Check In</th>
                            <th class="p-3 text-left text-sm">Check Out</th>
                            <th class="p-3 text-left text-sm">Status</th>
                            <th class="p-3 text-left text-sm">Reson</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($attendances as $attendance)
                            <tr class="border-b">
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
                                    @if($attendance->check_in_at && $attendance->check_out_at)
                                        <span class="text-green-600 text-sm">Completed</span>
                                    @elseif($attendance->check_in_at)
                                        <span class="text-yellow-600 text-sm">Checked in</span>
                                    @else
                                        <span class="text-gray-500 text-sm">Absent</span>
                                    @endif
                                </td>
                                <td>
                                    {{$attendance->absence_reason ?? '—'}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    No attendance records
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>
            </div>

            {{-- Subordinates --}}
            @if($subordinates->count())
                <div>
                    <h2 class="text-lg font-semibold mb-3">
                        My Team
                    </h2>

                    <div class="bg-white shadow rounded overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="p-3 text-left text-sm">Name</th>
                                <th class="p-3 text-left text-sm">Position</th>
                                <th class="p-3 text-left text-sm">Email</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($subordinates as $member)
                                <tr class="border-b">
                                    <td class="p-3 font-medium">
                                        {{ $member->name }}
                                    </td>
                                    <td class="p-3">
                                        {{ $member->position?->name ?? '—' }}
                                    </td>
                                    <td class="p-3">
                                        {{ $member->email }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $subordinates->links() }}
                    </div>
                </div>
            @endif
        @endif

    </div>
@endsection
