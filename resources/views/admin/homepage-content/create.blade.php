<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Content Block') }}
            </h2>
            <a href="{{ route('homepage-content.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ← Back to Content Blocks
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('homepage-content.store') }}" method="POST" enctype="multipart/form-data" id="content-form">
                        @csrf

                        <!-- Block Type Selection -->
                        <div class="mb-6">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Content Block Type</label>
                            <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required onchange="toggleContentFields()">
                                <option value="">Select a content type</option>
                                <option value="hero">Hero Section</option>
                                <option value="text">Text Content</option>
                                <option value="video">Video Embed</option>
                                <option value="image">Image Block</option>
                                <option value="events">Events Listing</option>
                                <option value="html">Custom HTML</option>
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Block Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Block Title (Optional)</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('title') }}">
                            <p class="mt-1 text-sm text-gray-500">Internal name for this content block</p>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hero Section Fields -->
                        <div id="hero-fields" class="content-fields" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Hero Section Settings</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                                    <input type="text" id="hero_title" name="content[title]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.title') }}">
                                </div>
                                
                                <div>
                                    <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                                    <input type="text" id="hero_subtitle" name="content[subtitle]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.subtitle') }}">
                                </div>
                                
                                <div>
                                    <label for="hero_description" class="block text-sm font-medium text-gray-700 mb-2">Hero Description</label>
                                    <textarea id="hero_description" name="content[description]" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('content.description') }}</textarea>
                                </div>
                                
                                <div>
                                    <label for="hero_button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                                    <input type="text" id="hero_button_text" name="content[button_text]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.button_text') }}">
                                </div>
                                
                                <div>
                                    <label for="hero_background" class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                                    <input type="file" id="hero_background" name="hero_background" accept="image/*" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <p class="mt-1 text-sm text-gray-500">Upload a background image for the hero section</p>
                                </div>
                            </div>
                        </div>

                        <!-- Text Content Fields -->
                        <div id="text-fields" class="content-fields" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Text Content Settings</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="text_title" class="block text-sm font-medium text-gray-700 mb-2">Section Title</label>
                                    <input type="text" id="text_title" name="content[title]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.title') }}">
                                </div>
                                
                                <div>
                                    <label for="text_content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                    <textarea id="text_content" name="content[text]" rows="8" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('content.text') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">Use the rich text editor to format your content</p>
                                </div>
                            </div>
                        </div>

                        <!-- Video Embed Fields -->
                        <div id="video-fields" class="content-fields" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Video Embed Settings</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="video_title" class="block text-sm font-medium text-gray-700 mb-2">Video Title</label>
                                    <input type="text" id="video_title" name="content[title]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.title') }}">
                                </div>
                                
                                <div>
                                    <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                                    <input type="url" id="video_url" name="content[video_url]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.video_url') }}">
                                    <p class="mt-1 text-sm text-gray-500">YouTube or Vimeo URL (e.g., https://youtube.com/watch?v=...)</p>
                                </div>
                                
                                <div>
                                    <label for="video_description" class="block text-sm font-medium text-gray-700 mb-2">Video Description</label>
                                    <textarea id="video_description" name="content[description]" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('content.description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Image Block Fields -->
                        <div id="image-fields" class="content-fields" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Image Block Settings</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="image_title" class="block text-sm font-medium text-gray-700 mb-2">Image Title</label>
                                    <input type="text" id="image_title" name="content[title]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.title') }}">
                                </div>
                                
                                <div>
                                    <label for="image_file" class="block text-sm font-medium text-gray-700 mb-2">Image File</label>
                                    <input type="file" id="image_file" name="image_file" accept="image/*" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                </div>
                                
                                <div>
                                    <label for="image_alt" class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                                    <input type="text" id="image_alt" name="content[alt_text]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.alt_text') }}">
                                </div>
                                
                                <div>
                                    <label for="image_caption" class="block text-sm font-medium text-gray-700 mb-2">Caption</label>
                                    <textarea id="image_caption" name="content[caption]" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('content.caption') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Events Listing Fields -->
                        <div id="events-fields" class="content-fields" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Events Listing Settings</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="events_title" class="block text-sm font-medium text-gray-700 mb-2">Section Title</label>
                                    <input type="text" id="events_title" name="content[title]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.title', 'Upcoming Events') }}">
                                </div>
                                
                                <div>
                                    <label for="events_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Section Subtitle</label>
                                    <input type="text" id="events_subtitle" name="content[subtitle]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.subtitle', 'Register for exciting events happening soon!') }}">
                                </div>
                                
                                <div>
                                    <label for="events_limit" class="block text-sm font-medium text-gray-700 mb-2">Number of Events to Show</label>
                                    <input type="number" id="events_limit" name="content[limit]" min="1" max="20" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.limit', 6) }}">
                                </div>
                            </div>
                        </div>

                        <!-- HTML Content Fields -->
                        <div id="html-fields" class="content-fields" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Custom HTML Block</h3>
                            
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
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="html_description" class="block text-sm font-medium text-gray-700 mb-2">Block Description</label>
                                    <input type="text" id="html_description" name="content[description]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" value="{{ old('content.description') }}" placeholder="Internal description for this HTML block">
                                    <p class="mt-1 text-sm text-gray-500">Brief description of what this HTML block contains (for admin reference)</p>
                                </div>
                                
                                <div>
                                    <label for="html_content" class="block text-sm font-medium text-gray-700 mb-2">HTML Content</label>
                                    <textarea id="html_content" name="content[html]" rows="12" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono text-sm" placeholder="<div class=&quot;custom-section&quot;>
    <h2>Your Custom Content</h2>
    <p>Add your HTML content here...</p>
</div>">{{ old('content.html') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">Write your custom HTML content. Use semantic HTML elements for better accessibility.</p>
                                </div>
                                
                                <div>
                                    <label for="html_css" class="block text-sm font-medium text-gray-700 mb-2">Custom CSS (Optional)</label>
                                    <textarea id="html_css" name="content[css]" rows="8" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono text-sm" placeholder=".custom-section {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
}

.custom-section h2 {
    color: #333;
    margin-bottom: 1rem;
}">{{ old('content.css') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">Add custom CSS styles for your HTML content. Styles will be scoped to this block.</p>
                                </div>
                                
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
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
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6 mt-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active (show on homepage)
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('homepage-content.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create Content Block') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleContentFields() {
            const type = document.getElementById('type').value;
            const allFields = document.querySelectorAll('.content-fields');
            
            // Hide all fields
            allFields.forEach(field => field.style.display = 'none');
            
            // Show relevant fields
            if (type) {
                const targetField = document.getElementById(type + '-fields');
                if (targetField) {
                    targetField.style.display = 'block';
                }
            }
        }
        
        // Initial load
        document.addEventListener('DOMContentLoaded', function() {
            toggleContentFields();
        });
    </script>
    @endpush
</x-app-layout>

@push('scripts')
<!-- Quill.js WYSIWYG Editor (Free & Open Source) -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    // Initialize Quill editor when document is ready
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Initialize editor when text content type is selected
        const typeSelect = document.getElementById('type');
        if (typeSelect) {
            typeSelect.addEventListener('change', function() {
                setTimeout(initializeQuillEditor, 100);
            });
        }
        
        // Try to initialize on page load
        setTimeout(initializeQuillEditor, 100);
    });
</script>
@endpush