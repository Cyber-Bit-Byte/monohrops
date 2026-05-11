@extends('layouts.admin')

@section('page-title', __('Earnings'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('earnings.create') }}" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Add Earning') }}</a>
                    <div class="text-xl font-bold">
                        {{ __('Total Earnings') }}: {{ number_format($totalEarning, 2) }}
                    </div>
                </div>
                <form method="GET" action="{{ route('earnings.index') }}" class="flex gap-4 mb-4">
                    <div>
                        <label for="department_id" class="block text-sm font-medium">{{ __('Department') }}</label>
                        <select name="department_id" id="department_id" class="border rounded px-2 py-1 w-full">
                            <option value="">{{ __('All Departments') }}</option>
                            @foreach($departments ?? \App\Models\Department::all() as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium">{{ __('Type') }}</label>
                        <select name="type" id="type" class="border rounded px-2 py-1 w-full">
                            <option value="">{{ __('All Types') }}</option>
                            <option value="Account receivable" {{ request('type') == 'Account receivable' ? 'selected' : '' }}>Account Receivable</option>
                            <option value="Cash" {{ request('type') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Advance" {{ request('type') == 'Advance' ? 'selected' : '' }}>Advance</option>
                        </select>
                    </div>
                    <div>
                        <label for="from_date" class="block text-sm font-medium">{{ __('From Date') }}</label>
                        <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="border rounded px-2 py-1 w-full">
                    </div>
                    <div>
                        <label for="to_date" class="block text-sm font-medium">{{ __('To Date') }}</label>
                        <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="border rounded px-2 py-1 w-full">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">{{ __('Filter') }}</button>
                        <a href="{{ route('earnings.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">{{ __('Clear') }}</a>
                    </div>
                </form>
                <table class="min-w-full table-auto mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">{{ __('Employee') }}</th>
                            <th class="px-4 py-2">{{ __('Department') }}</th>
                            <th class="px-4 py-2">{{ __('Type') }}</th>
                            <th class="px-4 py-2">{{ __('Amount') }}</th>
                            <th class="px-4 py-2">{{ __('Date') }}</th>
                            <th class="px-4 py-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($earnings as $earning)
                            <tr>
                                <td class="border px-4 py-2">{{ $earning->employee->name }}</td>
                                <td class="border px-4 py-2">{{ $earning->department->name ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $earning->type }}</td>
                                <td class="border px-4 py-2">{{ number_format($earning->amount, 2) }}</td>
                                <td class="border px-4 py-2">{{ $earning->date->format('Y-m-d') }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('earnings.show', $earning) }}" class="text-blue-500">{{ __('View') }}</a>
                                    <a href="{{ route('earnings.edit', $earning) }}" class="text-green-500 ml-2">{{ __('Edit') }}</a>
                                    <form action="{{ route('earnings.destroy', $earning) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure you want to delete this earning?')">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $earnings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
