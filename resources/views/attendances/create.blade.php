@extends('layouts.admin')

@section('page-title', __('Mark Attendance'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form action="{{ route('attendances.store') }}" method="POST">
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
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Date') }}</label>
                        <input id="date" name="date" type="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="mt-1 block w-full" required>
                        @error('date') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="check_in" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Check In') }}</label>
                        <input id="check_in" name="check_in" type="time" value="{{ old('check_in', '09:00') }}" class="mt-1 block w-full" required>
                        @error('check_in') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="check_out" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Check Out') }}</label>
                        <input id="check_out" name="check_out" type="time" value="{{ old('check_out', '17:00') }}" class="mt-1 block w-full">
                        @error('check_out') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                        <select id="status" name="status" class="mt-1 block w-full" required>
                            <option value="present" {{ old('status', 'present') == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="half-day" {{ old('status') == 'half-day' ? 'selected' : '' }}>Half Day</option>
                            <option value="leave" {{ old('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                        </select>
                        @error('status') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Notes') }}</label>
                        <textarea id="notes" name="notes" class="mt-1 block w-full">{{ old('notes') }}</textarea>
                        @error('notes') <div class="text-red-500">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded">{{ __('Save Attendance') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
