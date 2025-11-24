<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Content Block
            </h2>
            <a href="{{ route('homepage-content.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Content
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('homepage-content.update', $contentBlock) }}" method="POST" enctype="multipart/form-data" id="contentForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $contentBlock->title) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Content Type</label>
                            <select name="type" id="type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="hero" {{ old('type', $contentBlock->type) == 'hero' ? 'selected' : '' }}>Hero Section</option>
                                <option value="text" {{ old('type', $contentBlock->type) == 'text' ? 'selected' : '' }}>Text Content</option>
                                <option value="video" {{ old('type', $contentBlock->type) == 'video' ? 'selected' : '' }}>Video Embed</option>
                                <option value="image" {{ old('type', $contentBlock->type) == 'image' ? 'selected' : '' }}>Image</option>
                                <option value="events" {{ old('type', $contentBlock->type) == 'events' ? 'selected' : '' }}>Events List</option>
                                <option value="html" {{ old('type', $contentBlock->type) == 'html' ? 'selected' : '' }}>Custom HTML</option>
                                <option value="form" {{ old('type', $contentBlock->type) == 'form' ? 'selected' : '' }}>Form Block</option>
                            </select>
                        </div>

        <!-- Hero Section Fields -->
        <div id="hero-fields" style="display: none;">
            <div class="mb-4">
                <label for="hero_show_content" class="flex items-center">
                    <input type="checkbox" name="content[show_content]" id="hero_show_content" value="1"
                           {{ old('content.show_content', $contentBlock->content['show_content'] ?? true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Show text content over hero image</span>
                </label>
            </div>
            
            <div id="hero-content-fields" class="space-y-4">
                <div class="mb-4">
                    <label for="hero_subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
                    <input type="text" name="content[subtitle]" id="hero_subtitle" 
                           value="{{ old('content.subtitle', $contentBlock->content['subtitle'] ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>                            <div class="mb-4">
                                <label for="hero_image" class="block text-sm font-medium text-gray-700">Hero Image</label>
                                @if($contentBlock->type == 'hero' && isset($contentBlock->content['image']))
                                    <div class="mb-2">
                                        <img src="{{ $contentBlock->getHeroImageUrl() }}" alt="Current hero image" class="w-32 h-32 object-cover rounded">
                                        <p class="text-sm text-gray-600 mt-1">Current image</p>
                                    </div>
                                @endif
                                <input type="file" name="hero_image" id="hero_image" accept="image/*"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="text-sm text-gray-600 mt-1">Upload a new image to replace the current one (optional)</p>
                            </div>
                            
                <div class="mb-4">
                    <label for="hero_show_button" class="flex items-center">
                        <input type="checkbox" name="content[show_button]" id="hero_show_button" value="1"
                               {{ old('content.show_button', $contentBlock->content['show_button'] ?? false) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Show call-to-action button</span>
                    </label>
                </div>
                
                <div id="hero-button-fields" class="space-y-4">
                    <div class="mb-4">
                        <label for="hero_button_text" class="block text-sm font-medium text-gray-700">Button Text</label>
                        <input type="text" name="content[button_text]" id="hero_button_text" 
                               value="{{ old('content.button_text', $contentBlock->content['button_text'] ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="hero_button_link" class="block text-sm font-medium text-gray-700">Button Link</label>
                        <input type="text" name="content[button_link]" id="hero_button_link" 
                               value="{{ old('content.button_link', $contentBlock->content['button_link'] ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>
        </div>                        <!-- Text Content Fields -->
                        <div id="text-fields" style="display: none;">
                            <div class="mb-4">
                                <label for="text_content" class="block text-sm font-medium text-gray-700">Content</label>
                                <textarea name="content[text]" id="text_content" rows="6"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content.text', $contentBlock->content['text'] ?? '') }}</textarea>
                                <p class="text-sm text-gray-600 mt-1">Use the rich text editor to format your content</p>
                            </div>
                        </div>

                        <!-- Video Embed Fields -->
                        <div id="video-fields" style="display: none;">
                            <div class="mb-4">
                                <label for="video_url" class="block text-sm font-medium text-gray-700">YouTube Video URL</label>
                                <input type="url" name="content[video_url]" id="video_url" 
                                       value="{{ old('content.video_url', $contentBlock->content['video_url'] ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="https://www.youtube.com/watch?v=...">
                                <p class="text-sm text-gray-600 mt-1">Enter a YouTube video URL</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="video_description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="content[description]" id="video_description" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content.description', $contentBlock->content['description'] ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Image Fields -->
                        <div id="image-fields" style="display: none;">
                            <div class="mb-4">
                                <label for="image_file" class="block text-sm font-medium text-gray-700">Image</label>
                                @if($contentBlock->type == 'image' && $contentBlock->getImageUrl())
                                    <div class="mb-2">
                                        <img src="{{ $contentBlock->getImageUrl() }}" alt="Current image" class="w-32 h-32 object-cover rounded">
                                        <p class="text-sm text-gray-600 mt-1">Current image</p>
                                    </div>
                                @endif
                                <input type="file" name="image_file" id="image_file" accept="image/*"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="text-sm text-gray-600 mt-1">Upload a new image to replace the current one (optional)</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="image_alt" class="block text-sm font-medium text-gray-700">Alt Text</label>
                                <input type="text" name="content[alt_text]" id="image_alt" 
                                       value="{{ old('content.alt_text', $contentBlock->content['alt_text'] ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="image_caption" class="block text-sm font-medium text-gray-700">Caption</label>
                                <input type="text" name="content[caption]" id="image_caption" 
                                       value="{{ old('content.caption', $contentBlock->content['caption'] ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Events Fields -->
                        <div id="events-fields" style="display: none;">
                            <div class="mb-4">
                                <label for="events_limit" class="block text-sm font-medium text-gray-700">Number of Events to Show</label>
                                <input type="number" name="content[limit]" id="events_limit" min="1" max="20"
                                       value="{{ old('content.limit', $contentBlock->content['limit'] ?? 6) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="category_filter" class="block text-sm font-medium text-gray-700">Category Filter</label>
                                <select id="category_filter" name="content[category_filter]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="all" {{ old('content.category_filter', $contentBlock->content['category_filter'] ?? 'all') == 'all' ? 'selected' : '' }}>All Categories</option>
                                    <option value="specific" {{ old('content.category_filter', $contentBlock->content['category_filter'] ?? '') == 'specific' ? 'selected' : '' }}>Specific Categories</option>
                                    <option value="exclude" {{ old('content.category_filter', $contentBlock->content['category_filter'] ?? '') == 'exclude' ? 'selected' : '' }}>Exclude Categories</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Choose how to filter events by category</p>
                            </div>
                            
                            <div id="category-selection" class="mb-4" style="display: {{ in_array(old('content.category_filter', $contentBlock->content['category_filter'] ?? ''), ['specific', 'exclude']) ? 'block' : 'none' }};">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Categories</label>
                                <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                                    @foreach($categories as $category)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="content[selected_categories][]" value="{{ $category->id }}" 
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                   {{ in_array($category->id, old('content.selected_categories', $contentBlock->content['selected_categories'] ?? [])) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-900 flex items-center">
                                                <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $category->color }}"></span>
                                                {{ $category->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Select which categories to include or exclude from the events listing</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="events_show_past" class="flex items-center">
                                    <input type="checkbox" name="content[show_past]" id="events_show_past" value="1"
                                           {{ old('content.show_past', $contentBlock->content['show_past'] ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Include past events</span>
                                </label>
                            </div>
                        </div>

                        <!-- HTML Content Fields -->
                        <div id="html-fields" style="display: none;">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Developer Warning</h3>
                                        <div class="mt-1 text-sm text-yellow-700">
                                            <p>This feature allows raw HTML and CSS input. Only use trusted content to avoid security risks.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="html_description" class="block text-sm font-medium text-gray-700">Block Description</label>
                                <input type="text" name="content[description]" id="html_description" 
                                       value="{{ old('content.description', $contentBlock->content['description'] ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Internal description for this HTML block">
                                <p class="mt-1 text-sm text-gray-500">Brief description of what this HTML block contains (for admin reference)</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="html_content" class="block text-sm font-medium text-gray-700">HTML Content</label>
                                <textarea name="content[html]" id="html_content" rows="12" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm"
                                          placeholder="<div class=&quot;custom-section&quot;>
    <h2>Your Custom Content</h2>
    <p>Add your HTML content here...</p>
</div>">{{ old('content.html', $contentBlock->content['html'] ?? '') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Write your custom HTML content. Use semantic HTML elements for better accessibility.</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="html_css" class="block text-sm font-medium text-gray-700">Custom CSS (Optional)</label>
                                <textarea name="content[css]" id="html_css" rows="8" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm"
                                          placeholder=".custom-section {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
}

.custom-section h2 {
    color: #333;
    margin-bottom: 1rem;
}">{{ old('content.css', $contentBlock->content['css'] ?? '') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Add custom CSS styles for your HTML content. Styles will be scoped to this block.</p>
                            </div>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <h4 class="text-sm font-medium text-blue-800 mb-2">HTML Block Tips:</h4>
                                <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                                    <li>Use responsive design classes (Tailwind CSS is available)</li>
                                    <li>Test your HTML on different screen sizes</li>
                                    <li>Avoid inline JavaScript for security reasons</li>
                                    <li>Use semantic HTML elements for better SEO</li>
                                    <li>Ensure proper accessibility attributes (alt text, ARIA labels)</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Form Block Fields -->
                        <div id="form-fields" style="display: none;">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <p class="text-sm text-blue-800">Edit your form configuration. Submissions are saved to a CSV file that you can download from the submissions page.</p>
                                @if($contentBlock->type === 'form' && $contentBlock->formSubmission)
                                    <a href="{{ route('form-submissions.index', $contentBlock) }}" class="mt-2 inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                        View {{ $contentBlock->formSubmission->submission_count }} submission(s) →
                                    </a>
                                @endif
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="form_title_edit" class="block text-sm font-medium text-gray-700">Form Title</label>
                                    <input type="text" name="content[form_title]" id="form_title_edit" 
                                           value="{{ old('content.form_title', $contentBlock->content['form_title'] ?? '') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                           placeholder="Join Our Mailing List">
                                </div>
                                
                                <div>
                                    <label for="form_description_edit" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="content[description]" id="form_description_edit" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="Sign up to receive updates about our upcoming events.">{{ old('content.description', $contentBlock->content['description'] ?? '') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Form Fields</label>
                                    <div id="form-fields-container-edit" class="space-y-4">
                                        <!-- Existing fields will be loaded here -->
                                    </div>
                                    <button type="button" onclick="addFormFieldEdit()" class="mt-3 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-xs">
                                        + Add Field
                                    </button>
                                </div>

                                <div>
                                    <label for="submit_button_text_edit" class="block text-sm font-medium text-gray-700">Submit Button Text</label>
                                    <input type="text" name="content[submit_button_text]" id="submit_button_text_edit" 
                                           value="{{ old('content.submit_button_text', $contentBlock->content['submit_button_text'] ?? 'Submit') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label for="success_message_edit" class="block text-sm font-medium text-gray-700">Success Message</label>
                                    <input type="text" name="content[success_message]" id="success_message_edit" 
                                           value="{{ old('content.success_message', $contentBlock->content['success_message'] ?? 'Thank you for your submission!') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="content[collect_ip]" value="1" 
                                               {{ old('content.collect_ip', $contentBlock->content['collect_ip'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                        <span class="ml-2 text-sm text-gray-600">Collect IP addresses with submissions</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Location Fields -->
                        </div>

                        <div class="mb-4 ml-5">
                            <label for="is_active" class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                       {{ old('is_active', $contentBlock->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Active (visible on homepage)</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between mb-4 ml-5 mr-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Content Block
                            </button>
                            
                            <a href="{{ route('homepage-content.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        let fieldCounterEdit = 0;

        function addFormFieldEdit() {
            fieldCounterEdit++;
            const container = document.getElementById('form-fields-container-edit');
            const fieldHtml = `
                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50" id="field-edit-${fieldCounterEdit}">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="text-sm font-semibold text-gray-700">Field #${fieldCounterEdit}</h4>
                        <button type="button" onclick="removeFormFieldEdit(${fieldCounterEdit})" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Field Type</label>
                            <select name="content[fields][${fieldCounterEdit}][type]" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                <option value="text">Text</option>
                                <option value="email">Email</option>
                                <option value="tel">Phone</option>
                                <option value="number">Number</option>
                                <option value="textarea">Text Area</option>
                                <option value="select">Dropdown</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="radio">Radio Buttons</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Field Label</label>
                            <input type="text" name="content[fields][${fieldCounterEdit}][label]" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="e.g., Email Address" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Placeholder</label>
                            <input type="text" name="content[fields][${fieldCounterEdit}][placeholder]" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="e.g., your@email.com">
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input type="checkbox" name="content[fields][${fieldCounterEdit}][required]" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                <span class="ml-2 text-xs text-gray-600">Required field</span>
                            </label>
                        </div>
                    </div>
                    <input type="hidden" name="content[fields][${fieldCounterEdit}][id]" value="${fieldCounterEdit}">
                </div>
            `;
            container.insertAdjacentHTML('beforeend', fieldHtml);
        }

        function removeFormFieldEdit(id) {
            const field = document.getElementById('field-edit-' + id);
            if (field) {
                field.remove();
            }
        }

        function loadExistingFields() {
            const contentBlock = @json($contentBlock->content ?? []);
            if (contentBlock.fields) {
                const container = document.getElementById('form-fields-container-edit');
                container.innerHTML = '';
                
                // Convert fields object/array to array
                const fieldsArray = Array.isArray(contentBlock.fields) 
                    ? contentBlock.fields 
                    : Object.values(contentBlock.fields);
                
                fieldsArray.forEach((field) => {
                    if (!field || typeof field !== 'object') return;
                    
                    fieldCounterEdit++;
                    const fieldId = field.id || fieldCounterEdit;
                    const fieldHtml = `
                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50" id="field-edit-${fieldCounterEdit}">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="text-sm font-semibold text-gray-700">Field #${fieldCounterEdit}</h4>
                                <button type="button" onclick="removeFormFieldEdit(${fieldCounterEdit})" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Field Type</label>
                                    <select name="content[fields][${fieldCounterEdit}][type]" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                        <option value="text" ${field.type === 'text' ? 'selected' : ''}>Text</option>
                                        <option value="email" ${field.type === 'email' ? 'selected' : ''}>Email</option>
                                        <option value="tel" ${field.type === 'tel' ? 'selected' : ''}>Phone</option>
                                        <option value="number" ${field.type === 'number' ? 'selected' : ''}>Number</option>
                                        <option value="textarea" ${field.type === 'textarea' ? 'selected' : ''}>Text Area</option>
                                        <option value="select" ${field.type === 'select' ? 'selected' : ''}>Dropdown</option>
                                        <option value="checkbox" ${field.type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                                        <option value="radio" ${field.type === 'radio' ? 'selected' : ''}>Radio Buttons</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Field Label</label>
                                    <input type="text" name="content[fields][${fieldCounterEdit}][label]" value="${escapeHtml(field.label || '')}" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="e.g., Email Address" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Placeholder</label>
                                    <input type="text" name="content[fields][${fieldCounterEdit}][placeholder]" value="${escapeHtml(field.placeholder || '')}" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="e.g., your@email.com">
                                </div>
                                <div class="flex items-center">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="content[fields][${fieldCounterEdit}][required]" value="1" ${field.required ? 'checked' : ''} class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                        <span class="ml-2 text-xs text-gray-600">Required field</span>
                                    </label>
                                </div>
                            </div>
                            <input type="hidden" name="content[fields][${fieldCounterEdit}][id]" value="${fieldId}">
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', fieldHtml);
                });
            }
        }
        
        // Helper function to escape HTML
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const heroFields = document.getElementById('hero-fields');
            const textFields = document.getElementById('text-fields');
            const videoFields = document.getElementById('video-fields');
            const imageFields = document.getElementById('image-fields');
            const eventsFields = document.getElementById('events-fields');
            const htmlFields = document.getElementById('html-fields');
            const formFields = document.getElementById('form-fields');
            
            // Hero section toggles
            const heroShowContent = document.getElementById('hero_show_content');
            const heroContentFields = document.getElementById('hero-content-fields');
            const heroShowButton = document.getElementById('hero_show_button');
            const heroButtonFields = document.getElementById('hero-button-fields');

            function showFields() {
                // Hide all fields first
                heroFields.style.display = 'none';
                textFields.style.display = 'none';
                videoFields.style.display = 'none';
                imageFields.style.display = 'none';
                eventsFields.style.display = 'none';
                htmlFields.style.display = 'none';
                formFields.style.display = 'none';

                // Show relevant fields based on selection
                const selectedType = typeSelect.value;
                
                switch(selectedType) {
                    case 'hero':
                        heroFields.style.display = 'block';
                        toggleHeroContentFields();
                        toggleHeroButtonFields();
                        break;
                    case 'text':
                        textFields.style.display = 'block';
                        break;
                    case 'video':
                        videoFields.style.display = 'block';
                        break;
                    case 'image':
                        imageFields.style.display = 'block';
                        break;
                    case 'events':
                        eventsFields.style.display = 'block';
                        updateCategoryFilter();
                        break;
                    case 'html':
                        htmlFields.style.display = 'block';
                        break;
                    case 'form':
                        formFields.style.display = 'block';
                        loadExistingFields();
                        break;
                }
            }
            
            function toggleHeroContentFields() {
                if (heroShowContent && heroContentFields) {
                    heroContentFields.style.display = heroShowContent.checked ? 'block' : 'none';
                }
            }
            
            function toggleHeroButtonFields() {
                if (heroShowButton && heroButtonFields) {
                    heroButtonFields.style.display = heroShowButton.checked ? 'block' : 'none';
                }
            }
            
            function updateCategoryFilter() {
                const filterType = document.getElementById('category_filter');
                const categorySelection = document.getElementById('category-selection');
                
                if (filterType && categorySelection) {
                    if (filterType.value === 'specific' || filterType.value === 'exclude') {
                        categorySelection.style.display = 'block';
                    } else {
                        categorySelection.style.display = 'none';
                    }
                }
            }

            // Show fields on page load
            showFields();

            // Show fields when type changes
            typeSelect.addEventListener('change', showFields);
            
            // Toggle hero content fields
            if (heroShowContent) {
                heroShowContent.addEventListener('change', toggleHeroContentFields);
            }
            
            // Toggle hero button fields
            if (heroShowButton) {
                heroShowButton.addEventListener('change', toggleHeroButtonFields);
            }
            
            // Add event listener for category filter
            const categoryFilter = document.getElementById('category_filter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', updateCategoryFilter);
            }
        });
    </script>

    @push('scripts')
    <!-- Quill.js WYSIWYG Editor (Free & Open Source) -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        // Initialize Quill editor after field visibility logic
        setTimeout(function() {
            let quillEditor = null;
            const textContentTextarea = document.getElementById('text_content');
            
            function initializeQuillEditor() {
                // Only initialize if the text fields are visible and editor hasn't been created
                const textFields = document.getElementById('text-fields');
                if (textFields && textFields.style.display !== 'none' && !quillEditor) {
                    // Create editor container
                    const editorContainer = document.createElement('div');
                    editorContainer.id = 'quill-editor';
                    editorContainer.style.height = '200px';
                    
                    // Insert editor container after the textarea
                    textContentTextarea.style.display = 'none';
                    textContentTextarea.parentNode.insertBefore(editorContainer, textContentTextarea.nextSibling);
                    
                    // Initialize Quill
                    quillEditor = new Quill('#quill-editor', {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                [{ 'header': [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline'],
                                [{ 'color': [] }, { 'background': [] }],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                [{ 'align': [] }],
                                ['link', 'image'],
                                ['clean']
                            ]
                        },
                        placeholder: 'Start typing your content here...'
                    });
                    
                    // Set initial content from textarea
                    if (textContentTextarea.value) {
                        quillEditor.root.innerHTML = textContentTextarea.value;
                    }
                    
                    // Update textarea when content changes
                    quillEditor.on('text-change', function() {
                        textContentTextarea.value = quillEditor.root.innerHTML;
                    });
                    
                    // Ensure content is saved before form submission
                    const form = document.getElementById('contentForm');
                    if (form) {
                        form.addEventListener('submit', function() {
                            if (quillEditor) {
                                textContentTextarea.value = quillEditor.root.innerHTML;
                            }
                        });
                    }
                }
            }
            
            // Initialize editor when content type changes to text
            const typeSelect = document.getElementById('type');
            if (typeSelect) {
                typeSelect.addEventListener('change', function() {
                    // Destroy existing editor if switching away from text
                    if (quillEditor && typeSelect.value !== 'text') {
                        const editorContainer = document.getElementById('quill-editor');
                        if (editorContainer) {
                            editorContainer.remove();
                        }
                        textContentTextarea.style.display = 'block';
                        quillEditor = null;
                    }
                    // Initialize editor if switching to text
                    setTimeout(initializeQuillEditor, 100);
                });
            }
            
            // Try to initialize on page load
            initializeQuillEditor();
        }, 200);
    </script>
    @endpush
</x-app-layout>