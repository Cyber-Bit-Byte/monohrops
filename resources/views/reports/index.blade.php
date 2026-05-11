@extends('layouts.admin')

@section('page-title', __('Reports'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="GET" action="{{ route('reports.index') }}" class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('From') }}</label>
                        <input id="from" name="from" type="date" value="{{ $from }}" class="mt-1 block w-full">
                    </div>
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('To') }}</label>
                        <input id="to" name="to" type="date" value="{{ $to }}" class="mt-1 block w-full">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Refresh') }}</button>
                    </div>
                </form>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-300">{{ __('Salary Total') }}</h3>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($reports['salary_total'], 2) }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-300">{{ __('Cost Total') }}</h3>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($reports['cost_total'], 2) }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-300">{{ __('Earning Total') }}</h3>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($reports['earning_total'], 2) }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-300">{{ __('Attendance Entries') }}</h3>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $reports['attendance_count'] }}</p>
                    </div>
                </div>

                @if(!empty($reports['cost_by_department']))
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Cost Breakdown by Department') }}</h3>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left">{{ __('Department') }}</th>
                                        <th class="px-4 py-2 text-right">{{ __('Total Cost') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports['cost_by_department'] as $department => $total)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $department }}</td>
                                        <td class="border px-4 py-2 text-right">${{ number_format($total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('reports.export', ['from' => $from, 'to' => $to]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">{{ __('Export Salary CSV') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
