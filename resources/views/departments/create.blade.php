@extends('layouts.admin')

@section('page-title', __('Add Department'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Department Name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-200 dark:bg-gray-700 dark:text-gray-300" required>
                    @error('name') <div class="text-red-500">{{ $message }}</div> @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Save Department') }}</button>
                    <a href="{{ route('departments.index') }}" class="text-gray-600 hover:text-gray-800 dark:text-gray-400">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
