@extends('layouts.admin')

@section('page-title', __('Edit User'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full" required>
                    @error('name') <div class="text-red-500">{{ $message }}</div> @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full" required>
                    @error('email') <div class="text-red-500">{{ $message }}</div> @enderror
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                    <select id="role" name="role" class="mt-1 block w-full" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                    @error('role') <div class="text-red-500">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">Update User</button>
            </form>
        </div>
    </div>
</div>
@endsection
