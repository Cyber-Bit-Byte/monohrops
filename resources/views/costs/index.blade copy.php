@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Costs') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex gap-4 mb-4">
                    <a href="{{ route('costs.create') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">{{ __('Add Cost') }}</a>
                    <a href="{{ route('costs.report') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">{{ __('Report') }}</a>
                </div>
                <table class="min-w-full table-auto mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">{{ __('Employee') }}</th>
                            <th class="px-4 py-2">{{ __('Department') }}</th>
                            <th class="px-4 py-2">{{ __('Cost Type') }}</th>
                            <th class="px-4 py-2">{{ __('Description') }}</th>
                            <th class="px-4 py-2">{{ __('Amount') }}</th>
                            <th class="px-4 py-2">{{ __('Date') }}</th>
                            <th class="px-4 py-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($costs as $cost)
                            <tr>
                                <td class="border px-4 py-2">{{ $cost->employee->name }}</td>
                                <td class="border px-4 py-2">{{ $cost->department }}</td>
                                <td class="border px-4 py-2">{{ $cost->cost_type }}</td>
                                <td class="border px-4 py-2">{{ $cost->description }}</td>
                                <td class="border px-4 py-2">{{ number_format($cost->amount, 2) }}</td>
                                <td class="border px-4 py-2">{{ $cost->date->format('Y-m-d') }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('costs.show', $cost) }}" class="text-blue-500">{{ __('View') }}</a>
                                    <a href="{{ route('costs.edit', $cost) }}" class="text-green-500 ml-2">{{ __('Edit') }}</a>
                                    <form action="{{ route('costs.destroy', $cost) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 ml-2">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- {{ $costs->links() }} --}}
            </div>
        </div>
    </div>
</div>
@endsection