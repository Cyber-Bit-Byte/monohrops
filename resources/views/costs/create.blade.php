@extends('layouts.admin')

@section('page-title', __('Add Cost'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form action="{{ route('costs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Employee') }}</label>
                        <select id="employee_id" name="employee_id" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select employee') }}</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('employee_id') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Department') }}</label>
                        <select id="department_id" name="department_id" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select department') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="cost_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost Type') }}</label>
                        <select id="cost_type" name="cost_type" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select cost type') }}</option>
                            @foreach(\App\Models\Cost::COST_TYPES as $type)
                                <option value="{{ $type }}" {{ old('cost_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('cost_type') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                        <input id="description" name="description" value="{{ old('description') }}" class="mt-1 block w-full" required>
                        @error('description') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Amount') }}</label>
                        <input id="amount" name="amount" type="number" step="0.01" value="{{ old('amount') }}" class="mt-1 block w-full" required>
                        @error('amount') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Date') }}</label>
                        <input id="date" name="date" type="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="mt-1 block w-full" required>
                        @error('date') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Notes') }}</label>
                        <textarea id="notes" name="notes" class="mt-1 block w-full">{{ old('notes') }}</textarea>
                        @error('notes') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="document" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Document (Image or PDF)') }}</label>
                        <input id="document" name="document" type="file" class="mt-1 block w-full" accept=".jpg,.jpeg,.png,.pdf">
                        @error('document') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Save Cost') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
