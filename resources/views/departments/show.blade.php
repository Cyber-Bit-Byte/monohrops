@extends('layouts.admin')

@section('page-title', __('Department Details'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('Department Information') }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('ID') }}</label>
                    <div class="mt-1 text-gray-800 dark:text-gray-200">{{ $department->id }}</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                    <div class="mt-1 text-gray-800 dark:text-gray-200">{{ $department->name }}</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Created At') }}</label>
                    <div class="mt-1 text-gray-800 dark:text-gray-200">{{ $department->created_at->format('Y-m-d H:i:s') }}</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Updated At') }}</label>
                    <div class="mt-1 text-gray-800 dark:text-gray-200">{{ $department->updated_at->format('Y-m-d H:i:s') }}</div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('departments.edit', $department) }}" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Edit Department') }}</a>
                <a href="{{ route('departments.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 dark:text-gray-400">{{ __('Back to List') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
