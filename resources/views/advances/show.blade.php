@extends('layouts.admin')

@section('page-title', __('Advance Details'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="font-semibold">{{ __('Employee') }}</dt>
                        <dd>{{ $advance->employee->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Type') }}</dt>
                        <dd>{{ $advance->type_label }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Amount') }}</dt>
                        <dd>${{ number_format($advance->amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Date') }}</dt>
                        <dd>{{ $advance->date->format('Y-m-d') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-semibold">{{ __('Description') }}</dt>
                        <dd>{{ $advance->description ?: 'N/A' }}</dd>
                    </div>
                </dl>
                <div class="mt-6">
                    <a href="{{ route('advances.index') }}" class="text-blue-500">{{ __('Back to list') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
