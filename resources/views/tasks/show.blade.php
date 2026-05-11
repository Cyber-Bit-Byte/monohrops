@extends('layouts.admin')

@section('page-title', __('Task Details'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="font-semibold">{{ __('Creator') }}</dt>
                        <dd>{{ $task->employee->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Assignee') }}</dt>
                        <dd>{{ $task->assignee->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Department') }}</dt>
                        <dd>{{ $task->department->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Task Type') }}</dt>
                        <dd>{{ $task->taskType->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Title') }}</dt>
                        <dd>{{ $task->title }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Due Date') }}</dt>
                        <dd>{{ $task->due_date->format('Y-m-d') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Status') }}</dt>
                        <dd>{{ $task->status }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-semibold">{{ __('Description') }}</dt>
                        <dd>{{ $task->description }}</dd>
                    </div>
                </dl>
                <div class="mt-6">
                    <a href="{{ route('tasks.index') }}" class="text-blue-500">{{ __('Back to list') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
