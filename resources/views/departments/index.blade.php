@extends('layouts.admin')

@section('page-title', __('Departments'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('departments.create') }}" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Add Department') }}</a>
            </div>

                <table class="min-w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">{{ __('ID') }}</th>
                        <th class="px-4 py-2">{{ __('Name') }}</th>
                        <th class="px-4 py-2">{{ __('Created At') }}</th>
                        <th class="px-4 py-2">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $department)
                        <tr>
                            <td class="border px-4 py-2">{{ $department->id }}</td>
                            <td class="border px-4 py-2">{{ $department->name }}</td>
                            <td class="border px-4 py-2">{{ $department->created_at->format('Y-m-d') }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('departments.show', $department) }}" class="text-blue-500">{{ __('View') }}</a>
                                <a href="{{ route('departments.edit', $department) }}" class="text-green-500 ml-2">{{ __('Edit') }}</a>
                                <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure you want to delete this department?')">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $departments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
