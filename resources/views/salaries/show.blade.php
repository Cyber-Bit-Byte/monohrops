@extends('layouts.admin')

@section('page-title', __('Salary Details'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="font-semibold">{{ __('Employee') }}</dt>
                        <dd>{{ $salary->employee->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Amount') }}</dt>
                        <dd>{{ number_format($salary->amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Date') }}</dt>
                        <dd>{{ $salary->date->format('Y-m-d') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-semibold">{{ __('Notes') }}</dt>
                        <dd>{{ $salary->notes }}</dd>
                    </div>
                </dl>
                <div class="mt-6">
                    <a href="{{ route('salaries.index') }}" class="text-blue-500">{{ __('Back to list') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
