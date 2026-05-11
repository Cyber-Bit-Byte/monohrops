@extends('layouts.admin')

@section('page-title', __('Daily Attendance'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daily Attendance</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Date: {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</p>
                </div>
                
                <form method="GET" action="{{ route('attendances.daily') }}" class="flex flex-col sm:flex-row gap-3">
                    <div>
                        <input type="date" name="date" value="{{ $date }}" class="border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    </div>
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-medium py-2 px-4 rounded">Filter</button>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Employee') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Department') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Check In') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Check Out') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($employees as $employee)
                            @php
                                $attendance = $attendances->where('employee_id', $employee->id)->first();
                            @endphp
                            <tr class="{{ $attendance ? '' : 'bg-red-50 dark:bg-red-900/30' }}">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $employee->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $employee->department->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm {{ $attendance && $attendance->status === 'late' ? 'text-orange-600 dark:text-orange-400 font-medium' : 'text-gray-900 dark:text-gray-100' }}">
                                    {!! $attendance?->check_in ?? '-' !!}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{!! $attendance?->check_out ?? '-' !!}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($attendance)
                                        @if($attendance->status === 'present')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">{{ ucfirst($attendance->status) }}</span>
                                        @elseif($attendance->status === 'absent')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">{{ ucfirst($attendance->status) }}</span>
                                        @elseif($attendance->status === 'late')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">{{ ucfirst($attendance->status) }}</span>
                                        @elseif($attendance->status === 'half-day')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">{{ ucfirst($attendance->status) }}</span>
                                        @endif
                                    @else
                                        <span class="text-red-600 dark:text-red-400 font-medium">No Record</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($attendance)
                                        <a href="{{ route('attendances.show', $attendance) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ __('View') }}</a>
                                        <a href="{{ route('attendances.edit', $attendance) }}" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 ml-2">{{ __('Edit') }}</a>
                                    @else
                                        <a href="{{ route('attendances.create', ['employee_id' => $employee->id, 'date' => $date]) }}" class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300">{{ __('Add') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No employees found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $attendances->whereNotNull('id')->count() }}</span> of <span class="font-medium text-gray-900 dark:text-gray-100">{{ $employees->count() }}</span> employees recorded
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
