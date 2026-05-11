@extends('layouts.guest')

@section('content')
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">HR Operations</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Sign in to manage your workforce</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-200 dark:bg-gray-700 dark:text-gray-300">
            @error('email')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-200 dark:bg-gray-700 dark:text-gray-300">
            @error('password')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4 flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-600 text-cyan-600 shadow-sm focus:ring-cyan-500">
            <label for="remember_me" class="ms-2 block text-sm text-gray-700 dark:text-gray-300">Remember me</label>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-between mt-6">
            <button type="submit"
                class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded transition duration-150">
                Log in
            </button>
        </div>
    </form>
@endsection
