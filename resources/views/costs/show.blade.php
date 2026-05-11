@extends('layouts.admin')

@section('page-title', __('Cost Details'))

@section('content')
<div class="py-6">
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="font-semibold">{{ __('Employee') }}</dt>
                        <dd>{{ $cost->employee->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Department') }}</dt>
                        <dd>{{ $cost->department->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Cost Type') }}</dt>
                        <dd>{{ $cost->cost_type }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Description') }}</dt>
                        <dd>{{ $cost->description }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Amount') }}</dt>
                        <dd>{{ number_format($cost->amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">{{ __('Date') }}</dt>
                        <dd>{{ $cost->date->format('Y-m-d') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-semibold">{{ __('Notes') }}</dt>
                        <dd>{{ $cost->notes }}</dd>
                    </div>
                    @if($cost->document)
                    <div class="sm:col-span-2">
                        <dt class="font-semibold">{{ __('Document') }}</dt>
                        <dd class="mt-2">
                            @if(strtolower(pathinfo($cost->document, PATHINFO_EXTENSION)) === 'pdf')
                                <a href="{{ asset('storage/' . $cost->document) }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-6 h-6 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    {{ __('View PDF') }}
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $cost->document) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $cost->document) }}" alt="{{ __('Document') }}" class="max-w-full h-auto max-h-64 rounded border">
                                </a>
                            @endif
                        </dd>
                    </div>
                    @endif
                </dl>
                <div class="mt-6">
                    <a href="{{ route('costs.index') }}" class="text-blue-500">{{ __('Back to list') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
