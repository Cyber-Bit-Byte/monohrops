@extends('layouts.admin')

@section('page-title', __('Edit Advance'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form action="{{ route('advances.update', $advance) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employee</label>
                        <select id="employee_id" name="employee_id" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select employee') }}</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id', $advance->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }} ({{ $employee->email }})</option>
                            @endforeach
                        </select>
                        @error('employee_id') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Type') }}</label>
                        <select id="type" name="type" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select type') }}</option>
                            @foreach(\App\Models\Advance::TYPES as $key => $label)
                                <option value="{{ $key }}" {{ old('type', $advance->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Amount') }}</label>
                        <input id="amount" name="amount" type="number" step="0.01" value="{{ old('amount', $advance->amount) }}" class="mt-1 block w-full" required>
                        @error('amount') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Date') }}</label>
                        <input id="date" name="date" type="date" value="{{ old('date', $advance->date->format('Y-m-d')) }}" class="mt-1 block w-full" required>
                        @error('date') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                        <textarea id="description" name="description" class="mt-1 block w-full" rows="4">{{ old('description', $advance->description) }}</textarea>
                        @error('description') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Update Advance') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
