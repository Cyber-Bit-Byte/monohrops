@extends('layouts.admin')

@section('page-title', __('Employees'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="overflow-x-auto">
                <a href="{{ route('employees.create') }}" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">Add Employee</a>
                <table class="min-w-full table-auto mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Position</th>
                            <th class="px-4 py-2">Department</th>
                            <th class="px-4 py-2">Sequence</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td class="border px-4 py-2">{{ $employee->name }}</td>
                            <td class="border px-4 py-2">{{ $employee->email }}</td>
                            <td class="border px-4 py-2">{{ $employee->position }}</td>
                             <td class="border px-4 py-2">{{ $employee->department->name ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $employee->sequenc }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('employees.show', $employee) }}" class="text-blue-500">View</a>
                                <a href="{{ route('employees.edit', $employee) }}" class="text-green-500 ml-2">Edit</a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
