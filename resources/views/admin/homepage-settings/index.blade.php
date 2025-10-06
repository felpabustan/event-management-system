<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Homepage Settings') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Customize Homepage Content</h3>
                        <p class="text-sm text-gray-600">Update the content, colors, and text displayed on your homepage. Changes will take effect immediately.</p>
                    </div>

                    <form action="{{ route('homepage-settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @foreach($settings as $groupName => $groupSettings)
                            <div class="mb-8">
                                <h4 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 capitalize">
                                    {{ str_replace('_', ' ', $groupName) }} Settings
                                </h4>
                                
                                <div class="grid grid-cols-1 gap-6">
                                    @foreach($groupSettings as $setting)
                                        <div>
                                            <label for="settings_{{ $setting['key'] }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $setting['label'] }}
                                            </label>
                                            
                                            @if($setting['description'])
                                                <p class="text-xs text-gray-500 mb-2">{{ $setting['description'] }}</p>
                                            @endif
                                            
                                            @if($setting['type'] === 'textarea')
                                                <textarea 
                                                    id="settings_{{ $setting['key'] }}" 
                                                    name="settings[{{ $setting['key'] }}]" 
                                                    rows="3" 
                                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                >{{ old('settings.' . $setting['key'], $setting['value']) }}</textarea>
                                            @elseif($setting['type'] === 'select' && $setting['key'] === 'hero_gradient_from')
                                                <select 
                                                    id="settings_{{ $setting['key'] }}" 
                                                    name="settings[{{ $setting['key'] }}]" 
                                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                >
                                                    @foreach(['blue-600', 'indigo-600', 'purple-600', 'pink-600', 'red-600', 'orange-600', 'yellow-600', 'green-600', 'teal-600', 'cyan-600'] as $color)
                                                        <option value="{{ $color }}" {{ $setting['value'] === $color ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('-', ' ', $color)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @elseif($setting['type'] === 'select' && $setting['key'] === 'hero_gradient_to')
                                                <select 
                                                    id="settings_{{ $setting['key'] }}" 
                                                    name="settings[{{ $setting['key'] }}]" 
                                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                >
                                                    @foreach(['purple-700', 'indigo-700', 'blue-700', 'pink-700', 'red-700', 'orange-700', 'yellow-700', 'green-700', 'teal-700', 'cyan-700'] as $color)
                                                        <option value="{{ $color }}" {{ $setting['value'] === $color ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('-', ' ', $color)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input 
                                                    type="text" 
                                                    id="settings_{{ $setting['key'] }}" 
                                                    name="settings[{{ $setting['key'] }}]" 
                                                    value="{{ old('settings.' . $setting['key'], $setting['value']) }}" 
                                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                />
                                            @endif
                                            
                                            @error('settings.' . $setting['key'])
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7l-10 10"></path>
                                    </svg>
                                    Preview Homepage
                                </a>
                            </div>
                            <x-primary-button>
                                {{ __('Save Settings') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>