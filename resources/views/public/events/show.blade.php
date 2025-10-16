<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->title }} - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="bg-gray-50 min-h-screen">
        <!-- Header -->
        @auth
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-6">
                        <div class="flex items-center">
                            <a href="{{ route('home') }}"
                                class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</a>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('events.public.index') }}" class="text-gray-700 hover:text-gray-900">← Back to
                                Events</a>
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        </div>
                    </div>
                </div>
            </header>
        @endauth

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                @if (session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Event Details -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-6 py-6">
                                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 mr-3 h-5 w-5 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Date</p>
                                            <p class="text-sm text-gray-500">{{ $event->date->format('l, F j, Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- <div class="flex items-center">
                                        <svg class="flex-shrink-0 mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Time</p>
                                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}</p>
                                        </div>
                                    </div> --}}

                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 mr-3 h-5 w-5 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Venue</p>
                                            <p class="text-sm text-gray-500">{{ $event->venue }}</p>
                                        </div>
                                    </div>

                                    {{-- @if ($event->category)
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Category</p>
                                            <p class="text-sm text-gray-500 flex items-center">
                                                <span class="w-2 h-2 rounded-full mr-2" style="background-color: {{ $event->category->color }}"></span>
                                                {{ $event->category->name }}
                                                <span class="ml-2 text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                                    Max {{ $event->category->max_registrations_per_user }} per user
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    @endif --}}

                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 mr-3 h-5 w-5 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Capacity</p>
                                            <p class="text-sm text-gray-500">{{ $event->current_capacity }} /
                                                {{ $event->max_capacity }} registered</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 mr-3 h-5 w-5 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Price</p>
                                            @if ($event->isFree())
                                                <p class="text-sm text-green-600 font-semibold">Free</p>
                                            @else
                                                <p class="text-sm text-blue-600 font-semibold">
                                                    {{ $event->getFormattedPriceAttribute() }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">About this event</h3>
                                    <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                                </div>

                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">Registration Progress</span>
                                        <span class="text-sm text-gray-500">{{ $event->current_capacity }} /
                                            {{ $event->max_capacity }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full"
                                            style="width: {{ $event->max_capacity > 0 ? ($event->current_capacity / $event->max_capacity) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $event->availableSpots() }} spots
                                        remaining</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-6 py-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-lg font-medium text-gray-900">Event Registration</h2>
                                    @if ($event->isFree())
                                        <span
                                            class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Free
                                            Event</span>
                                    @else
                                        <span
                                            class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">{{ $event->getFormattedPriceAttribute() }}</span>
                                    @endif
                                </div>

                                @if ($event->isFull())
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-red-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Event Full</h3>
                                        <p class="mt-1 text-sm text-gray-500">This event has reached maximum capacity.
                                        </p>
                                    </div>
                                @elseif($event->date < now()->toDateString())
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Event Passed</h3>
                                        <p class="mt-1 text-sm text-gray-500">This event has already taken place.</p>
                                    </div>
                                @else
                                    @if ($event->isFree())
                                        <!-- Free Event Registration Form -->
                                        <form action="{{ route('events.register', $event) }}" method="POST"
                                            id="freeRegistrationForm">
                                            @csrf

                                            <div class="space-y-4">
                                                <div>
                                                    <label for="name"
                                                        class="block text-sm font-medium text-gray-700">Full
                                                        Name</label>
                                                    <input type="text" name="name" id="name"
                                                        value="{{ old('name') }}" required
                                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    @error('name')
                                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label for="email"
                                                        class="block text-sm font-medium text-gray-700">Email
                                                        Address</label>
                                                    <input type="email" name="email" id="email"
                                                        value="{{ old('email') }}" required
                                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    @error('email')
                                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label for="phone"
                                                        class="block text-sm font-medium text-gray-700">Phone
                                                        Number</label>
                                                    <input type="tel" name="phone" id="phone"
                                                        value="{{ old('phone') }}"
                                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    @error('phone')
                                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- reCAPTCHA v3 Token (hidden field) -->
                                                <input type="hidden" name="recaptcha_token"
                                                    id="recaptcha_token_free">
                                                @error('recaptcha_token')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror <button type="submit"
                                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Register for Free
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <!-- Paid Event Registration Form -->
                                        <div class="space-y-4">
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    <div>
                                                        <h4 class="text-sm font-medium text-blue-800">Paid Event</h4>
                                                        <p class="text-sm text-blue-600">Price:
                                                            {{ $event->getFormattedPriceAttribute() }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Registration form for all users -->
                                            <form action="{{ route('payment.checkout', $event) }}" method="POST"
                                                id="paymentForm">
                                                @csrf

                                                <div class="space-y-4 mb-4">
                                                    <div>
                                                        <label for="payment_name"
                                                            class="block text-sm font-medium text-gray-700">Full
                                                            Name</label>
                                                        <input type="text" name="name" id="payment_name"
                                                            value="{{ old('name') }}" required
                                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                        @error('name')
                                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div>
                                                        <label for="payment_email"
                                                            class="block text-sm font-medium text-gray-700">Email
                                                            Address</label>
                                                        <input type="email" name="email" id="payment_email"
                                                            value="{{ old('email') }}" required
                                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                        @error('email')
                                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div>
                                                        <label for="payment_phone"
                                                            class="block text-sm font-medium text-gray-700">Phone
                                                            Number (Optional)</label>
                                                        <input type="tel" name="phone" id="payment_phone"
                                                            value="{{ old('phone') }}"
                                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                        @error('phone')
                                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- reCAPTCHA v3 Token (hidden field) -->
                                                    <input type="hidden" name="recaptcha_token"
                                                        id="recaptcha_token_paid">
                                                    @error('recaptcha_token')
                                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <button type="submit"
                                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                        </path>
                                                    </svg>
                                                    Pay {{ $event->getFormattedPriceAttribute() }} & Register
                                                </button>
                                            </form>

                                            <div class="mt-4 p-3 bg-gray-50 rounded-md">
                                                <h4 class="text-sm font-medium text-gray-700 mb-2">Payment Information
                                                </h4>
                                                <ul class="text-xs text-gray-600 space-y-1">
                                                    <li>• Secure payment processing by Stripe</li>
                                                    <li>• You'll receive a confirmation email after payment</li>
                                                    <li>• Registration is confirmed immediately upon payment</li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mt-4 text-center">
                                        <p class="text-xs text-gray-500">
                                            By registering, you'll receive a confirmation email with event details.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
    <!-- reCAPTCHA v3 JavaScript -->
    <script>
        grecaptcha.ready(function() {
            // Handle free registration form
            const freeForm = document.getElementById('freeRegistrationForm');
            if (freeForm) {
                freeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                        action: 'free_registration'
                    }).then(function(token) {
                        document.getElementById('recaptcha_token_free').value = token;
                        freeForm.submit();
                    });
                });
            }

            // Handle paid registration form
            const paymentForm = document.getElementById('paymentForm');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                        action: 'paid_registration'
                    }).then(function(token) {
                        document.getElementById('recaptcha_token_paid').value = token;
                        paymentForm.submit();
                    });
                });
            }
        });
    </script>
</body>

</html>
