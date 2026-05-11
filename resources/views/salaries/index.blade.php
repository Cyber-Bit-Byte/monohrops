@extends('layouts.admin')

@section('page-title', __('Salaries'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <a href="{{ route('salaries.create') }}" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Add Salary') }}</a>
                <table class="min-w-full table-auto mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">{{ __('Employee') }}</th>
                            <th class="px-4 py-2">{{ __('Amount') }}</th>
                            <th class="px-4 py-2">{{ __('Date') }}</th>
                            <th class="px-4 py-2">{{ __('Notes') }}</th>
                            <th class="px-4 py-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salaries as $salary)
                            <tr>
                                <td class="border px-4 py-2">{{ $salary->employee->name }}</td>
                                <td class="border px-4 py-2">{{ number_format($salary->amount, 2) }}</td>
                                <td class="border px-4 py-2">{{ $salary->date->format('Y-m-d') }}</td>
                                <td class="border px-4 py-2">{{ $salary->notes }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('salaries.show', $salary) }}" class="text-blue-500">{{ __('View') }}</a>
                                    <a href="{{ route('salaries.edit', $salary) }}" class="text-green-500 ml-2">{{ __('Edit') }}</a>
                                    <form action="{{ route('salaries.destroy', $salary) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure you want to delete this salary?')">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $salaries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
