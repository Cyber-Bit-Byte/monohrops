@extends('layouts.admin')

@section('page-title', __('Tasks'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <a href="{{ route('tasks.create') }}" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Add Task') }}</a>
                <form method="GET" action="{{ route('tasks.index') }}" class="flex gap-4 mb-4 mt-4">
                    <div>
                        <label for="department_id" class="block text-sm font-medium">{{ __('Department') }}</label>
                        <select name="department_id" id="department_id" class="border rounded px-2 py-1 w-full">
                            <option value="">{{ __('All Departments') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="task_type" class="block text-sm font-medium">{{ __('Task Type') }}</label>
                        <select name="task_type" id="task_type" class="border rounded px-2 py-1 w-full">
                            <option value="">{{ __('All Task Types') }}</option>
                            <option value="write script" {{ request('task_type') == 'write script' ? 'selected' : '' }}>Write Script</option>
                            <option value="record video" {{ request('task_type') == 'record video' ? 'selected' : '' }}>Record Video</option>
                            <option value="edit video" {{ request('task_type') == 'edit video' ? 'selected' : '' }}>Edit Video</option>
                            <option value="publish video" {{ request('task_type') == 'publish video' ? 'selected' : '' }}>Publish Video</option>
                            <option value="video upload" {{ request('task_type') == 'video upload' ? 'selected' : '' }}>Video Upload</option>
                            <option value="graphic post" {{ request('task_type') == 'graphic post' ? 'selected' : '' }}>Graphic Post</option>
                            <option value="publish news" {{ request('task_type') == 'publish news' ? 'selected' : '' }}>Publish News</option>
                            <option value="purchase ingredients" {{ request('task_type') == 'purchase ingredients' ? 'selected' : '' }}>Purchase Ingredients</option>
                            <option value="purchase instrument" {{ request('task_type') == 'purchase instrument' ? 'selected' : '' }}>Purchase Instrument</option>
                            <option value="bajar" {{ request('task_type') == 'bajar' ? 'selected' : '' }}>Bajar</option>
                            <option value="contact" {{ request('task_type') == 'contact' ? 'selected' : '' }}>Contact</option>
                            <option value="others" {{ request('task_type') == 'others' ? 'selected' : '' }}>Others</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium">{{ __('Status') }}</label>
                        <select name="status" id="status" class="border rounded px-2 py-1 w-full">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                        <a href="{{ route('tasks.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">{{ __('Clear') }}</a>
                    </div>
                </form>
                <table class="min-w-full table-auto mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">{{ __('Creator') }}</th>
                            <th class="px-4 py-2">{{ __('Assignee') }}</th>
                            <th class="px-4 py-2">{{ __('Department') }}</th>
                            <th class="px-4 py-2">{{ __('Task Type') }}</th>
                            <th class="px-4 py-2">{{ __('Title') }}</th>
                            <th class="px-4 py-2">{{ __('Due Date') }}</th>
                            <th class="px-4 py-2">{{ __('Status') }}</th>
                            <th class="px-4 py-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                            <tr>
                                <td class="border px-4 py-2">{{ $task->employee->name }}</td>
                                <td class="border px-4 py-2">{{ $task->assignee->name ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $task->department->name ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $task->task_type ?: '-' }}</td>
                                <td class="border px-4 py-2">{{ $task->title }}</td>
                                <td class="border px-4 py-2">{{ $task->due_date->format('Y-m-d') }}</td>
                                <td class="border px-4 py-2">{{ $task->status }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-blue-500">{{ __('View') }}</a>
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-green-500 ml-2">{{ __('Edit') }}</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure you want to delete this task?')">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
