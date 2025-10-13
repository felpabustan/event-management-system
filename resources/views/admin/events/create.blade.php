<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Event') }}
            </h2>
            <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ← Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('events.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Title -->
                            <div>
                                <x-input-label for="title" :value="__('Event Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <!-- Date and Time -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="date" :value="__('Date')" />
                                    <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('date')" />
                                </div>
                                <div>
                                    <x-input-label for="time" :value="__('Time')" />
                                    <x-text-input id="time" name="time" type="time" class="mt-1 block w-full" :value="old('time')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('time')" />
                                </div>
                            </div>

                            <!-- Venue -->
                            <div>
                                <x-input-label for="venue" :value="__('Venue')" />
                                <x-text-input id="venue" name="venue" type="text" class="mt-1 block w-full" :value="old('venue')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('venue')" />
                            </div>

                            <!-- Max Capacity -->
                            <div>
                                <x-input-label for="max_capacity" :value="__('Maximum Capacity')" />
                                <x-text-input id="max_capacity" name="max_capacity" type="number" min="1" class="mt-1 block w-full" :value="old('max_capacity')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('max_capacity')" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-input-label for="category_id" :value="__('Category (Optional)')" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Select a Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}
                                                style="color: {{ $category->color }}">
                                            {{ $category->name }} (Max {{ $category->max_registrations_per_user }} per user)
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">
                                    Categories help organize events and limit how many events of the same type a user can register for.
                                </p>
                                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                            </div>

                            <!-- Pricing Section -->
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Event Pricing</h3>
                                
                                <!-- Is Paid Toggle -->
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" id="is_paid" name="is_paid" value="1" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                                               {{ old('is_paid') ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">This is a paid event</span>
                                    </label>
                                </div>

                                <!-- Price and Currency (shown when is_paid is checked) -->
                                <div id="pricing-fields" class="space-y-4" style="display: {{ old('is_paid') ? 'block' : 'none' }};">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="price" :value="__('Price')" />
                                            <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                                        </div>
                                        <div>
                                            <x-input-label for="currency" :value="__('Currency')" />
                                            <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                <option value="SGD" {{ old('currency') == 'SGD' ? 'selected' : '' }}>SGD - Singaporean Dollar</option>
                                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                                <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                                <option value="CAD" {{ old('currency') == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                                <option value="AUD" {{ old('currency') == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                                        </div>
                                    </div>
                                    
                                    <!-- Stripe Price ID -->
                                    <div>
                                        <x-input-label for="stripe_price_id" :value="__('Stripe Price ID')" />
                                        <x-text-input id="stripe_price_id" name="stripe_price_id" type="text" class="mt-1 block w-full" :value="old('stripe_price_id')" placeholder="price_1234567890abcdef" />
                                        <p class="mt-1 text-sm text-gray-500">
                                            Enter the Stripe Price ID from your Stripe Dashboard (e.g., price_1234567890abcdef). This connects your event to your Stripe product.
                                        </p>
                                        <x-input-error class="mt-2" :messages="$errors->get('stripe_price_id')" />
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end">
                                <x-primary-button class="ml-4">
                                    {{ __('Create Event') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isPaidCheckbox = document.getElementById('is_paid');
            const pricingFields = document.getElementById('pricing-fields');
            const priceInput = document.getElementById('price');

            function togglePricingFields() {
                if (isPaidCheckbox.checked) {
                    pricingFields.style.display = 'block';
                    priceInput.required = true;
                } else {
                    pricingFields.style.display = 'none';
                    priceInput.required = false;
                    priceInput.value = '';
                    document.getElementById('stripe_price_id').value = '';
                }
            }

            isPaidCheckbox.addEventListener('change', togglePricingFields);
            togglePricingFields(); // Initialize on page load
        });
    </script>
    @endpush
</x-app-layout>