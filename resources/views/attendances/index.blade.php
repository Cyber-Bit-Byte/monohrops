@extends('layouts.admin')

@section('page-title', __('Attendance'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <a href="{{ route('attendances.create') }}" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Add Attendance') }}</a>
                </div>
                
            <!-- Filters -->
            <form method="GET" action="{{ route('attendances.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Employee') }}</label>
                    <select name="employee_id" id="employee_id" class="border rounded px-3 py-2 w-full sm:w-48 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" onchange="this.form.submit()">
                        <option value="">{{ __('All Employees') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Month') }}</label>
                    <select name="month" id="month" class="border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" onchange="this.form.submit()">
                        <option value="">{{ __('All Months') }}</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2024, $m, 1)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Year') }}</label>
                    <select name="year" id="year" class="border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" onchange="this.form.submit()">
                        <option value="">{{ __('All Years') }}</option>
                        @foreach(range(now()->year, 2026) as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Date') }}</label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}" class="border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" onchange="this.form.submit()">
                </div>
            </form>
            </div>
            
            <!-- Active Filters -->
            @if(request()->has('employee_id') || request()->has('date') || request()->has('month') || request()->has('year'))
                <div class="flex flex-wrap gap-2 mb-4">
                    @if(request('employee_id'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200">
                            {{ __('Employee') }}: {{ $employees->firstWhere('id', request('employee_id'))->name ?? request('employee_id') }}
                            <a href="{{ route('attendances.index', array_merge(request()->except(['employee_id']), ['page' => null])) }}" class="ml-1 hover:text-cyan-600 dark:hover:text-cyan-400">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        </span>
                    @endif
                    @if(request('month'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ __('Month') }}: {{ \Carbon\Carbon::create(2024, request('month'), 1)->format('F') }}
                            <a href="{{ route('attendances.index', array_merge(request()->except(['month']), ['page' => null])) }}" class="ml-1 hover:text-blue-600 dark:hover:text-blue-400">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        </span>
                    @endif
                    @if(request('year'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            {{ __('Year') }}: {{ request('year') }}
                            <a href="{{ route('attendances.index', array_merge(request()->except(['year']), ['page' => null])) }}" class="ml-1 hover:text-green-600 dark:hover:text-green-400">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        </span>
                    @endif
                    @if(request('date'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            {{ __('Date') }}: {{ \Carbon\Carbon::parse(request('date'))->format('M d, Y') }}
                            <a href="{{ route('attendances.index', array_merge(request()->except(['date']), ['page' => null])) }}" class="ml-1 hover:text-indigo-600 dark:hover:text-indigo-400">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        </span>
                    @endif
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('Employee') }}</th>
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
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $attendance->employee->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $attendance->date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-sm {{ $attendance->status === 'late' ? 'text-orange-600 dark:text-orange-400 font-medium' : 'text-gray-900 dark:text-gray-100' }}">
                                    {!! $attendance->check_in !!}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{!! $attendance->check_out !!}</td>
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
                                    <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 ml-2" onclick="return confirm('{{ __('Are you sure you want to delete this attendance?') }}')">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No attendance records found.') }}
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
