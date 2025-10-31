<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Registration - {{ $event->title }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('events.show', $event) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Event
                    </a>
                </div>

                <!-- Event Info Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-blue-900 mb-2">Event Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-blue-800">Title:</span>
                            <span class="text-blue-700">{{ $event->title }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">Date:</span>
                            <span class="text-blue-700">{{ $event->date->format('M j, Y') }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">Capacity:</span>
                            <span class="text-blue-700">{{ $event->current_capacity }} / {{ $event->max_capacity }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">Type:</span>
                            <span class="text-blue-700">{{ $event->isFree() ? 'Free' : $event->getFormattedPriceAttribute() }}</span>
                        </div>
                        @if($event->category)
                        <div>
                            <span class="font-medium text-blue-800">Category:</span>
                            <span class="text-blue-700">{{ $event->category->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Capacity Warning -->
                @if($event->isFull())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium text-red-800">Event is Full</h3>
                                <p class="text-red-700 text-sm">This event has reached maximum capacity. You cannot add more registrations.</p>
                            </div>
                        </div>
                    </div>
                @elseif($event->max_capacity - $event->current_capacity <= 5)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77-.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium text-yellow-800">Limited Capacity</h3>
                                <p class="text-yellow-700 text-sm">Only {{ $event->max_capacity - $event->current_capacity }} spots remaining.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Registration Form -->
                @if(!$event->isFull())
                <form method="POST" action="{{ route('events.store-manual-registration', $event) }}" class="space-y-6">
                    @csrf

                    <!-- Personal Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Full Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                                              :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" 
                                              :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Phone -->
                            <div class="md:col-span-2">
                                <x-input-label for="phone" :value="__('Phone Number (Optional)')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" 
                                              :value="old('phone')" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Registration Status -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Registration Status</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Registration Status')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select Status</option>
                                    <option value="confirmed" {{ old('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                <p class="text-sm text-gray-600 mt-1">Only "Confirmed" registrations count towards event capacity.</p>
                            </div>

                            <!-- Payment Status -->
                            <div>
                                <x-input-label for="payment_status" :value="__('Payment Status')" />
                                <select id="payment_status" name="payment_status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">Select Payment Status</option>
                                    @if($event->isFree())
                                        <option value="free" selected>Free</option>
                                    @else
                                        <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="pending" {{ old('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('payment_status')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                        
                        <div>
                            <x-input-label for="notes" :value="__('Notes (Optional)')" />
                            <textarea id="notes" name="notes" rows="4" 
                                      class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Any additional notes about this registration...">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">Internal notes for admin reference.</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('events.show', $event) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                            Cancel
                        </a>
                        
                        <x-primary-button>
                            Add Registration
                        </x-primary-button>
                    </div>
                </form>
                @endif

            </div>
        </div>
    </div>
</div>

<script>
    // Auto-set payment status for free events
    document.addEventListener('DOMContentLoaded', function() {
        @if($event->isFree())
            document.getElementById('payment_status').disabled = true;
        @endif
    });
</script>
</x-app-layout>