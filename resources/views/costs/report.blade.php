@extends('layouts.admin')

@section('page-title', __('Cost Report'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="GET" action="{{ route('costs.report') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium">{{ __('Employee') }}</label>
                        <select name="employee_id" class="border rounded px-2 py-1 dark:bg-gray-700 dark:border-gray-600">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">{{ __('Cost Type') }}</label>
                        <select name="cost_type" class="border rounded px-2 py-1 dark:bg-gray-700 dark:border-gray-600">
                            <option value="">All Types</option>
                            @foreach(\App\Models\Cost::COST_TYPES as $type)
                                <option value="{{ $type }}" {{ request('cost_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">{{ __('Department') }}</label>
                        <select name="department_id" class="border rounded px-2 py-1 dark:bg-gray-700 dark:border-gray-600">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">{{ __('Date From') }}</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded px-2 py-1 dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">{{ __('Date To') }}</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded px-2 py-1 dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Filter') }}</button>
                    <a href="{{ route('costs.report') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Reset</a>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-bold mb-4">{{ __('Total Cost') }}</h3>
                <p class="text-2xl font-bold text-green-600">{{ number_format($total, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">{{ __('Cost by Type') }}</h3>
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 text-left">{{ __('Type') }}</th>
                                <th class="px-2 py-1 text-right">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($byType as $type => $amount)
                                <tr class="border-t">
                                    <td class="px-2 py-1">{{ $type }}</td>
                                    <td class="px-2 py-1 text-right">{{ number_format($amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">{{ __('Cost by Department') }}</h3>
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 text-left">{{ __('Department') }}</th>
                                <th class="px-2 py-1 text-right">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($byDepartment as $dept => $amount)
                                <tr class="border-t">
                                    <td class="px-2 py-1">{{ $dept }}</td>
                                    <td class="px-2 py-1 text-right">{{ number_format($amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-bold mb-4">{{ __('Cost Details') }}</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">{{ __('Employee') }}</th>
                            <th class="px-4 py-2">{{ __('Department') }}</th>
                            <th class="px-4 py-2">{{ __('Cost Type') }}</th>
                            <th class="px-4 py-2">{{ __('Description') }}</th>
                            <th class="px-4 py-2">{{ __('Amount') }}</th>
                            <th class="px-4 py-2">{{ __('Date') }}</th>
                            <th class="px-4 py-2">{{ __('Document') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($costs as $cost)
                            <tr>
                                <td class="border px-4 py-2">{{ $cost->employee->name }}</td>
                                <td class="border px-4 py-2">{{ $cost->department->name ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $cost->cost_type }}</td>
                                <td class="border px-4 py-2">{{ $cost->description }}</td>
                                <td class="border px-4 py-2">{{ number_format($cost->amount, 2) }}</td>
                                <td class="border px-4 py-2">{{ $cost->date->format('Y-m-d') }}</td>
                                <td class="border px-4 py-2">
                                    @if($cost->document)
                                        @if(strtolower(pathinfo($cost->document, PATHINFO_EXTENSION)) === 'pdf')
                                            <a href="{{ asset('storage/' . $cost->document) }}" target="_blank" class="text-blue-500 hover:text-blue-700" title="{{ __('View PDF') }}">
                                                <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                                </svg>
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $cost->document) }}" target="_blank" title="{{ __('View Image') }}">
                                                <img src="{{ asset('storage/' . $cost->document) }}" alt="{{ __('Document') }}" class="max-h-12 rounded border">
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border px-4 py-2 text-center">No costs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
