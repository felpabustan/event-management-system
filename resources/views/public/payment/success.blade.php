@extends('layouts.public')

@section('title', 'Payment Successful - ' . config('app.name'))

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
        <!-- Success Icon -->
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Success Message -->
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
        <p class="text-gray-600 mb-6">Your registration for <strong>{{ $event->title }}</strong> has been confirmed.</p>

        <!-- Event Details -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
            <h3 class="font-semibold text-gray-900 mb-4">Event Details</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Event:</span>
                    <span class="font-medium">{{ $event->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Date:</span>
                    <span class="font-medium">{{ $event->date->format('M j, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Time:</span>
                    <span class="font-medium">
                        @if($event->time)
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}
                        @else
                            Time TBD
                        @endif
                    </span>
                </div>
                @if($event->venue)
                <div class="flex justify-between">
                    <span class="text-gray-600">Location:</span>
                    <span class="font-medium">{{ $event->venue }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-600">Amount Paid:</span>
                    <span class="font-medium text-green-600">{{ $event->getFormattedPriceAttribute() }}</span>
                </div>
            </div>
        </div>

        <!-- Registration Details -->
        <div class="bg-blue-50 rounded-lg p-6 mb-6 text-left">
            <h3 class="font-semibold text-gray-900 mb-4">Registration Details</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Name:</span>
                    <span class="font-medium">{{ $registration->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium">{{ $registration->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Registration ID:</span>
                    <span class="font-medium">#{{ $registration->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">{{ ucfirst($registration->status) }}</span>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-yellow-50 rounded-lg p-6 mb-6 text-left">
            <h3 class="font-semibold text-gray-900 mb-2">What's Next?</h3>
            <ul class="text-gray-600 space-y-1 text-sm">
                <li>• You will receive a confirmation email shortly</li>
                <li>• Please save this page or take a screenshot for your records</li>
                <li>• If you have any questions, contact us at support@example.com</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('events.public.show', $event) }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Event
            </a>
            
            <a href="{{ route('events.public.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                View More Events
            </a>
        </div>
    </div>
</div>
@endsection