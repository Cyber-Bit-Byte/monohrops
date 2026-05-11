@extends('layouts.admin')

@section('page-title', __('Advances'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="GET" action="{{ route('advances.index') }}" class="grid gap-4 md:grid-cols-3 mb-6">
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('From') }}</label>
                        <input id="from" name="from" type="date" value="{{ $from }}" class="mt-1 block w-full">
                    </div>
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('To') }}</label>
                        <input id="to" name="to" type="date" value="{{ $to }}" class="mt-1 block w-full">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Filter') }}</button>
                    </div>
                </form>

                <div class="mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Net Amount') }}:
                        @if($totalAmount < 0)
                            -{{ number_format(abs($totalAmount), 2) }}
                        @else
                            {{ number_format($totalAmount, 2) }}
                        @endif
                    </h3>
                </div>

                <a href="{{ route('advances.create') }}" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">Add Advance</a>
                <table class="min-w-full table-auto mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Employee</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($advances as $advance)
                        <tr>
                            <td class="border px-4 py-2">{{ $advance->employee->name }}</td>
                            <td class="border px-4 py-2">{{ $advance->type_label }}</td>
                            <td class="border px-4 py-2">{{ number_format($advance->amount, 2) }}</td>
                            <td class="border px-4 py-2">{{ $advance->date->format('Y-m-d') }}</td>
                            <td class="border px-4 py-2">{{ Str::limit($advance->description, 50) }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('advances.show', $advance) }}" class="text-blue-500">View</a>
                                <a href="{{ route('advances.edit', $advance) }}" class="text-green-500 ml-2">Edit</a>
                                <form action="{{ route('advances.destroy', $advance) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure you want to delete this advance?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $advances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
