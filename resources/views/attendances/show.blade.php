@extends('layouts.admin')

@section('page-title', __('Attendance Details'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="font-semibold">{{ __('Employee') }}</dt>
                        <dd>{{ $attendance->employee->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Date') }}</dt>
                        <dd>{{ $attendance->date->format('Y-m-d') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Check In') }}</dt>
                        <dd>{{ $attendance->check_in }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Check Out') }}</dt>
                        <dd>{{ $attendance->check_out }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Status') }}</dt>
                        <dd>{{ $attendance->status }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-semibold">{{ __('Notes') }}</dt>
                        <dd>{{ $attendance->notes }}</dd>
                    </div>
                </dl>
                <div class="mt-6">
                    <a href="{{ route('attendances.index') }}" class="text-blue-500">{{ __('Back to list') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
