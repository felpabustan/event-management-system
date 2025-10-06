<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Homepage Content Blocks') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('homepage-content.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Content Block
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Manage Homepage Content</h3>
                        <p class="text-sm text-gray-600">Drag and drop to reorder content blocks. Changes will take effect immediately on the homepage.</p>
                    </div>

                    @if($blocks->count() > 0)
                        <div id="sortable-blocks" class="space-y-4">
                            @foreach($blocks as $block)
                                <div class="content-block bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition-colors cursor-move" data-id="{{ $block->id }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="drag-handle p-2 rounded hover:bg-gray-200 cursor-grab active:cursor-grabbing" title="Drag to reorder">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">
                                                    {{ $block->getTypeDisplayName() }}
                                                    @if($block->title)
                                                        - {{ $block->title }}
                                                    @endif
                                                </h4>
                                                <p class="text-sm text-gray-500">
                                                    @if($block->type === 'hero')
                                                        Hero section with title: {{ $block->content['title'] ?? 'No title' }}
                                                    @elseif($block->type === 'text')
                                                        Text content block
                                                    @elseif($block->type === 'video')
                                                        Video: {{ $block->content['video_url'] ?? 'No URL' }}
                                                    @elseif($block->type === 'image')
                                                        Image block
                                                    @elseif($block->type === 'events')
                                                        Events listing section
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $block->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $block->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <a href="{{ route('homepage-content.edit', $block) }}" class="text-blue-600 hover:text-blue-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('homepage-content.destroy', $block) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-500" onclick="return confirm('Are you sure you want to delete this content block?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No content blocks</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first content block.</p>
                            <div class="mt-6">
                                <a href="{{ route('homepage-content.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Content Block
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7l-10 10"></path>
                                    </svg>
                                    Preview Homepage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load SortableJS directly in the template -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    <!-- Custom styles for drag and drop -->
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background: #f3f4f6 !important;
            border: 2px dashed #d1d5db !important;
        }
    </style>
    
    <script>
        // Notification functions
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                        }
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Slide in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Slide out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Add loading state
        function setDragLoading(isLoading) {
            const container = document.getElementById('sortable-blocks');
            if (container) {
                if (isLoading) {
                    container.style.opacity = '0.7';
                    container.style.pointerEvents = 'none';
                } else {
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                }
            }
        }

        // Add a small delay to ensure DOM is fully loaded
        setTimeout(function() {
            console.log('Initializing SortableJS...');
            
            const sortableContainer = document.getElementById('sortable-blocks');
            console.log('Container found:', !!sortableContainer);
            
            if (sortableContainer) {
                console.log('Container children:', sortableContainer.children.length);
                
                // Try a very basic configuration first
                try {
                    const sortable = Sortable.create(sortableContainer, {
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        onStart: function(evt) {
                            console.log('🚀 DRAG STARTED!', evt.item);
                        },
                        onEnd: function(evt) {
                            console.log('✅ DRAG ENDED!', evt.oldIndex, '->', evt.newIndex);
                            
                            // Only update if position changed
                            if (evt.oldIndex !== evt.newIndex) {
                                setDragLoading(true);
                                
                                const blocks = [];
                                document.querySelectorAll('.content-block').forEach((block, index) => {
                                    blocks.push({
                                        id: parseInt(block.dataset.id),
                                        sort_order: index + 1
                                    });
                                });

                                console.log('Updating order:', blocks);

                                fetch('{{ route("homepage-content.update-order") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ blocks: blocks })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! status: ${response.status}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('✅ Order updated:', data);
                                    setDragLoading(false);
                                    showNotification('Content block order updated successfully!', 'success');
                                })
                                .catch(error => {
                                    console.error('❌ Update failed:', error);
                                    setDragLoading(false);
                                    showNotification('Failed to update content block order. Please try again.', 'error');
                                    
                                    // Optionally revert the visual change
                                    location.reload();
                                });
                            }
                        }
                    });
                    
                    console.log('✅ SortableJS initialized:', sortable);
                } catch (error) {
                    console.error('❌ SortableJS initialization failed:', error);
                    showNotification('Failed to initialize drag and drop functionality.', 'error');
                }
            } else {
                console.error('❌ Sortable container #sortable-blocks not found');
            }
        }, 100);
    </script>
</x-app-layout>