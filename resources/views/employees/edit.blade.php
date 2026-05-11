@extends('layouts.admin')

@section('page-title', __('Edit Employee'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form action="{{ route('employees.update', $employee) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User</label>
                        <select id="user_id" name="user_id" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select user') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                        <input id="name" name="name" value="{{ old('name', $employee->name) }}" class="mt-1 block w-full" required>
                        @error('name') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $employee->email) }}" class="mt-1 block w-full" required>
                        @error('email') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Position') }}</label>
                        <input id="position" name="position" value="{{ old('position', $employee->position) }}" class="mt-1 block w-full" required>
                        @error('position') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Department') }}</label>
                        <select id="department_id" name="department_id" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select department') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Hire Date') }}</label>
                        <input id="hire_date" name="hire_date" type="date" value="{{ old('hire_date', $employee->hire_date->format('Y-m-d')) }}" class="mt-1 block w-full" required>
                        @error('hire_date') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Salary') }}</label>
                        <input id="salary" name="salary" type="number" step="0.01" value="{{ old('salary', $employee->salary) }}" class="mt-1 block w-full">
                        @error('salary') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="sequenc" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Sequence') }}</label>
                        <input id="sequenc" name="sequenc" type="text" value="{{ old('sequenc', $employee->sequenc) }}" class="mt-1 block w-full">
                        @error('sequenc') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Update Employee') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
