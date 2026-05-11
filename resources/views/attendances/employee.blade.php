@extends('layouts.admin')

@section('page-title', __('Employee Attendance'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $employee->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->department->name ?? '-' }} | {{ $employee->position }}</p>
                </div>
                
                <form method="GET" action="{{ route('attendances.employee', $employee) }}" class="flex flex-col sm:flex-row gap-3">
                    <div>
                        <select name="month" class="border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="year" class="border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            @foreach(range(now()->year, 2026) as $y)
                                <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-medium py-2 px-4 rounded">Filter</button>
                </form>
            </div>
            
            <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                Showing <span class="font-medium text-gray-900 dark:text-gray-100">{{ $attendances->total() }}</span> attendance records
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Date') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Check In') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Check Out') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $attendance->date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-sm {{ $attendance->status === 'late' ? 'text-orange-600 dark:text-orange-400 font-medium' : 'text-gray-900 dark:text-gray-100' }}">{!! $attendance->check_in !!}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{!! $attendance->check_out ?? '-' !!}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($attendance->status === 'present')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">{{ ucfirst($attendance->status) }}</span>
                                    @elseif($attendance->status === 'absent')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">{{ ucfirst($attendance->status) }}</span>
                                    @elseif($attendance->status === 'late')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">{{ ucfirst($attendance->status) }}</span>
                                    @elseif($attendance->status === 'half-day')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">{{ ucfirst($attendance->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <a href="{{ route('attendances.show', $attendance) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ __('View') }}</a>
                                    <a href="{{ route('attendances.edit', $attendance) }}" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 ml-2">{{ __('Edit') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No attendance records found for this period.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="mt-4">
                    {{ $attendances->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
