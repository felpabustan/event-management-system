@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Upcoming Events</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="bg-gray-50">
        <!-- Header -->
        @auth
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">{{ \App\Models\HomepageSetting::getValue('site_name', config('app.name')) }}</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        @endauth

        <!-- Dynamic Content Blocks -->
        @foreach($contentBlocks as $contentBlock)
            @if($contentBlock->type === 'hero')
                <!-- Hero Section -->
                <div class="relative bg-gradient-to-r from-blue-600 to-purple-700 overflow-hidden">
                    <div class="absolute inset-0">
                        @if(isset($contentBlock->content['image']))
                            <img src="{{ $contentBlock->getHeroImageUrl() }}" alt="Hero background" class="w-full h-full object-cover min-h-screen">
                            @if($contentBlock->content['show_content'] ?? true)
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-800 to-purple-900 mix-blend-multiply opacity-70"></div>
                            @endif
                        @else
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-800 to-purple-900 mix-blend-multiply min-h-screen"></div>
                        @endif
                        
                        @if($contentBlock->content['show_content'] ?? true)
                            <!-- Decorative pattern -->
                            <div class="absolute inset-0 opacity-20">
                                <svg class="absolute inset-0 h-full w-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <polygon points="0,100 100,0 100,100"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    @if($contentBlock->content['show_content'] ?? true)
                        <!-- Content overlay -->
                        <div class="relative min-h-screen flex items-center justify-center">
                            <div class="max-w-7xl mx-auto px-4 py-24 sm:px-6 lg:px-8 lg:py-32">
                                <div class="text-center">
                                    <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                                        {{ $contentBlock->title }}
                                        @if(isset($contentBlock->content['subtitle']) && $contentBlock->content['subtitle'])
                                            <span class="block text-yellow-300">{{ $contentBlock->content['subtitle'] }}</span>
                                        @endif
                                    </h1>
                                    
                                    @if(($contentBlock->content['show_button'] ?? false) && isset($contentBlock->content['button_text']) && isset($contentBlock->content['button_link']))
                                        <div class="flex justify-center space-x-4">
                                            <a href="{{ $contentBlock->content['button_link'] }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out shadow-lg">
                                                {{ $contentBlock->content['button_text'] }}
                                                <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                            @guest
                                            <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 border-2 border-white text-base font-medium rounded-md text-white bg-transparent hover:bg-white hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition duration-150 ease-in-out shadow-lg">
                                                Admin Login
                                            </a>
                                            @endguest
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative elements -->
                        <div class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2">
                            <div class="w-64 h-64 bg-yellow-300 rounded-full opacity-20"></div>
                        </div>
                        <div class="absolute bottom-0 left-0 transform -translate-x-1/2 translate-y-1/2">
                            <div class="w-48 h-48 bg-purple-300 rounded-full opacity-20"></div>
                        </div>
                    @else
                        <!-- Pure image hero without content -->
                        <div class="relative w-full min-h-screen">
                            <!-- Image takes full viewport height without overlay -->
                        </div>
                    @endif
                </div>
            @elseif($contentBlock->type === 'text')
                <!-- Text Content Block -->
                <div class="bg-white py-16">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-3xl mx-auto text-center">
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ $contentBlock->title }}</h2>
                            <div class="text-lg text-gray-600 prose prose-lg mx-auto">
                                {!! $contentBlock->content['text'] ?? '' !!}
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($contentBlock->type === 'video')
                <!-- Video Content Block -->
                <div class="bg-gray-50 py-16">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-4xl mx-auto text-center">
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ $contentBlock->title }}</h2>
                            @if(isset($contentBlock->content['description']))
                                <p class="text-lg text-gray-600 mb-8">{{ $contentBlock->content['description'] }}</p>
                            @endif
                            @if(isset($contentBlock->content['video_url']))
                                <div class="relative w-full" style="padding-bottom: 56.25%; /* 16:9 aspect ratio */">
                                    <iframe src="{{ $contentBlock->getVideoEmbedUrl() }}" 
                                            class="absolute top-0 left-0 w-full h-full rounded-lg shadow-lg" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                    </iframe>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif($contentBlock->type === 'image')
                <!-- Image Content Block -->
                <div class="bg-white py-16">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-4xl mx-auto text-center">
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ $contentBlock->title }}</h2>
                            @if($contentBlock->getImageUrl())
                                <div class="mb-6">
                                    <img src="{{ $contentBlock->getImageUrl() }}" 
                                         alt="{{ $contentBlock->content['alt_text'] ?? $contentBlock->title }}" 
                                         class="w-full h-auto max-w-2xl mx-auto rounded-lg shadow-lg">
                                </div>
                            @endif
                            @if(isset($contentBlock->content['caption']) && $contentBlock->content['caption'])
                                <p class="text-lg text-gray-600">{{ $contentBlock->content['caption'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif($contentBlock->type === 'events')
                <!-- Events List Content Block -->
                <main id="events" class="max-w-7xl mx-auto py-12 sm:px-6 lg:px-8">
                    <div class="px-4 py-6 sm:px-0">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-900">{{ $contentBlock->title }}</h2>
                        </div>

                        @php
                            $eventsLimit = $contentBlock->content['limit'] ?? 6;
                            $showPast = $contentBlock->content['show_past'] ?? false;
                            
                            $eventsQuery = \App\Models\Event::query();
                            if (!$showPast) {
                                $eventsQuery->where('date', '>=', now()->toDateString());
                            }
                            $limitedEvents = $eventsQuery->orderBy('date', 'asc')->limit($eventsLimit)->get();
                        @endphp

                        @if($limitedEvents->count() > 0)
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($limitedEvents as $event)
                                    <div class="bg-white overflow-hidden shadow rounded-lg mt-6">
                                        <div class="p-6">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <h3 class="text-lg font-medium text-gray-900">{{ $event->title }}</h3>
                                                    <p class="text-sm text-gray-500">{{ $event->venue }}</p>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                                    <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $event->date->format('M j, Y') }} at {{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}
                                                </div>
                                                
                                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($event->description, 100) }}</p>
                                                
                                                <div class="flex items-center justify-between mb-4">
                                                    <span class="text-sm text-gray-500">
                                                        {{ $event->current_capacity }} / {{ $event->max_capacity }} registered
                                                    </span>
                                                    @if($event->isFull())
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Full</span>
                                                    @else
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                                                    @endif
                                                </div>
                                                
                                                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $event->max_capacity > 0 ? ($event->current_capacity / $event->max_capacity) * 100 : 0 }}%"></div>
                                                </div>
                                            </div>

                                            <div class="mt-6 flex space-x-3">
                                                <a href="{{ route('events.public.show', $event) }}" class="flex-1 bg-indigo-600 text-white text-center py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out block font-semibold shadow-lg">
                                                    View Details
                                                </a>
                                                @if(!$event->isFull())
                                                    <a href="{{ route('events.public.show', $event) }}" class="flex-1 bg-emerald-600 text-white text-center py-3 px-4 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition duration-150 ease-in-out block font-semibold shadow-lg">
                                                        Register Now
                                                    </a>
                                                @else
                                                    <span class="flex-1 bg-gray-400 text-white text-center py-3 px-4 rounded-lg font-semibold cursor-not-allowed shadow-lg">
                                                        Event Full
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No events available</h3>
                                <p class="mt-1 text-sm text-gray-500">Check back later for new events!</p>
                            </div>
                        @endif
                    </div>
                </main>
            @elseif($contentBlock->type === 'html')
                <!-- Custom HTML Content Block -->
                <div class="custom-html-block">
                    @if(isset($contentBlock->content['css']) && $contentBlock->content['css'])
                        <!-- Custom CSS for this HTML block -->
                        <style>
                            {{ $contentBlock->content['css'] }}
                        </style>
                    @endif
                    
                    @if(isset($contentBlock->content['html']) && $contentBlock->content['html'])
                        <!-- Custom HTML Content -->
                        {!! $contentBlock->content['html'] !!}
                    @endif
                </div>
            @endif
        @endforeach
        
        <!-- Fallback: If no content blocks exist, show events directly -->
        @if($contentBlocks->isEmpty())
            <!-- Main Content -->
            <main id="events" class="max-w-7xl mx-auto py-12 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">{{ \App\Models\HomepageSetting::getValue('events_section_title', 'Upcoming Events') }}</h2>
                        <p class="mt-2 text-lg text-gray-600">{{ \App\Models\HomepageSetting::getValue('events_section_subtitle', 'Register for exciting events happening soon!') }}</p>
                    </div>

                    @if($events->count() > 0)
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($events as $event)
                                <div class="bg-white overflow-hidden shadow rounded-lg mt-6">
                                    <div class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <h3 class="text-lg font-medium text-gray-900">{{ $event->title }}</h3>
                                                <p class="text-sm text-gray-500">{{ $event->venue }}</p>
                                                @if($event->isFree())
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 mt-1">Free</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mt-1">{{ $event->getFormattedPriceAttribute() }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $event->date->format('M j, Y') }} at {{ \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A') }}
                                            </div>
                                            
                                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($event->description, 100) }}</p>
                                            
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="text-sm text-gray-500">
                                                    {{ $event->current_capacity }} / {{ $event->max_capacity }} registered
                                                </span>
                                                @if($event->isFull())
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Full</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                                                @endif
                                            </div>
                                            
                                            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $event->max_capacity > 0 ? ($event->current_capacity / $event->max_capacity) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>

                                        <div class="mt-6 flex space-x-3">
                                            <a href="{{ route('events.public.show', $event) }}" class="flex-1 bg-indigo-600 text-white text-center py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out block font-semibold shadow-lg">
                                                View Details
                                            </a>
                                            @if(!$event->isFull())
                                                <a href="{{ route('events.public.show', $event) }}" class="flex-1 bg-emerald-600 text-white text-center py-3 px-4 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition duration-150 ease-in-out block font-semibold shadow-lg">
                                                    Register Now
                                                </a>
                                            @else
                                                <span class="flex-1 bg-gray-400 text-white text-center py-3 px-4 rounded-lg font-semibold cursor-not-allowed shadow-lg">
                                                    Event Full
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming events</h3>
                            <p class="mt-1 text-sm text-gray-500">Check back later for new events!</p>
                        </div>
                    @endif
                </div>
            </main>
        @endif

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>