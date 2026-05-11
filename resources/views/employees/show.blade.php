@extends('layouts.admin')

@section('page-title', __('Employee Details'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="font-semibold">{{ __('Name') }}</dt>
                        <dd>{{ $employee->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Email') }}</dt>
                        <dd>{{ $employee->email }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Position') }}</dt>
                        <dd>{{ $employee->position }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Department') }}</dt>
                        <dd>{{ $employee->department->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Hire Date') }}</dt>
                        <dd>{{ $employee->hire_date->format('Y-m-d') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Salary') }}</dt>
                        <dd>{{ number_format($employee->salary, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Sequence') }}</dt>
                        <dd>{{ $employee->sequenc }}</dd>
                    </div>
                </dl>
                <div class="mt-6">
                    <a href="{{ route('employees.index') }}" class="text-blue-500">{{ __('Back to list') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
