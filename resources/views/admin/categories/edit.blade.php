<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Category: {{ $category->name }}
            </h2>
            <a href="{{ route('admin.categories.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                        @csrf
                        @method('PUT')

                        <!-- Category Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Category Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description (Optional)')" />
                            <textarea id="description" name="description" rows="3" 
                                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $category->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Max Registrations Per User -->
                        <div class="mb-4">
                            <x-input-label for="max_registrations_per_user" :value="__('Maximum Registrations Per User')" />
                            <x-text-input id="max_registrations_per_user" name="max_registrations_per_user" type="number" 
                                          min="1" max="100" class="mt-1 block w-full" :value="old('max_registrations_per_user', $category->max_registrations_per_user)" required />
                            <p class="mt-1 text-sm text-gray-500">How many events in this category can a single user register for?</p>
                            <x-input-error class="mt-2" :messages="$errors->get('max_registrations_per_user')" />
                        </div>

                        <!-- Color -->
                        <div class="mb-4">
                            <x-input-label for="color" :value="__('Category Color')" />
                            <div class="mt-1 flex items-center space-x-3">
                                <input id="color" name="color" type="color" 
                                       value="{{ old('color', $category->color) }}" 
                                       class="h-10 w-20 border border-gray-300 rounded-md cursor-pointer" required />
                                <span class="text-sm text-gray-500">This color will be used to identify the category in the UI</span>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('color')" />
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" value="1" 
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active (can be selected for new events)
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.categories.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>