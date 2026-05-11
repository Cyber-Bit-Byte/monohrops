@extends('layouts.admin')

@section('page-title', __('User Details'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Name</label>
                    <p class="text-lg text-gray-800 dark:text-gray-100">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Email</label>
                    <p class="text-lg text-gray-800 dark:text-gray-100">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Role</label>
                    <p class="text-lg text-gray-800 dark:text-gray-100">{{ ucfirst($user->role) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Employee</label>
                    <p class="text-lg text-gray-800 dark:text-gray-100">{{ $user->employee ? $user->employee->name : 'Not assigned' }}</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('users.edit', $user) }}" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">Edit User</a>
                <a href="{{ route('users.index') }}" class="ml-4 text-gray-600 hover:text-gray-900">Back to Users</a>
            </div>
        </div>
    </div>
</div>
@endsection
