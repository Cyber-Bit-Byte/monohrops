@extends('layouts.admin')

@section('page-title', __('Add Employee'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form action="{{ route('employees.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User</label>
                        <select id="user_id" name="user_id" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select user') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full" required>
                        @error('name') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full" required>
                        @error('email') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Position</label>
                        <input type="text" name="position" id="position" value="{{ old('position') }}" class="mt-1 block w-full" required>
                        @error('position') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department</label>
                        <select id="department_id" name="department_id" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select department') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hire Date</label>
                        <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date') }}" class="mt-1 block w-full" required>
                        @error('hire_date') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Salary</label>
                        <input type="number" step="0.01" name="salary" id="salary" value="{{ old('salary') }}" class="mt-1 block w-full">
                        @error('salary') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="sequenc" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sequence</label>
                        <input type="text" name="sequenc" id="sequenc" value="{{ old('sequenc') }}" class="mt-1 block w-full">
                        @error('sequenc') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">Create Employee</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
