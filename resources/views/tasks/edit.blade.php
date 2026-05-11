@extends('layouts.admin')

@section('page-title', __('Edit Task'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="assignee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Assignee') }}</label>
                        <select id="assignee_id" name="assignee_id" class="mt-1 block w-full" required>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('assignee_id', $task->assignee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('assignee_id') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Department') }}</label>
                        <select id="department_id" name="department_id" class="mt-1 block w-full">
                            <option value="">{{ __('Select department') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $task->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="task_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Task Type') }}</label>
                        <select id="task_type_id" name="task_type_id" class="mt-1 block w-full">
                            <option value="">{{ __('Select task type') }}</option>
                            @foreach($taskTypes as $taskType)
                                <option value="{{ $taskType->id }}" {{ old('task_type_id', $task->task_type_id) == $taskType->id ? 'selected' : '' }}>{{ $taskType->name }}</option>
                            @endforeach
                        </select>
                        @error('task_type_id') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title') }}</label>
                        <input id="title" name="title" value="{{ old('title', $task->title) }}" class="mt-1 block w-full" required>
                        @error('title') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                        <textarea id="description" name="description" class="mt-1 block w-full">{{ old('description', $task->description) }}</textarea>
                        @error('description') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Due Date') }}</label>
                        <input id="due_date" name="due_date" type="date" value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}" class="mt-1 block w-full" required>
                        @error('due_date') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                        <select id="status" name="status" class="mt-1 block w-full" required>
                            <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $task->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Update Task') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
