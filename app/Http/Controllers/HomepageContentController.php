<?php

namespace App\Http\Controllers;

use App\Models\HomepageContentBlock;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HomepageContentController extends Controller
{
    /**
     * Display content blocks management page
     */
    public function index(): View
    {
        $blocks = HomepageContentBlock::getAllBlocks();
        return view('admin.homepage-content.index', compact('blocks'));
    }

    /**
     * Show form to create new content block
     */
    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.homepage-content.create', compact('categories'));
    }

    /**
     * Store new content block
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:hero,text,video,image,events,html,form',
            'title' => 'nullable|string|max:255',
            'content' => 'required|array',
            'is_active' => 'boolean',
        ]);

        // Handle file upload for hero background
        if ($request->hasFile('hero_background')) {
            $file = $request->file('hero_background');
            $path = $file->store('homepage/hero', 'public');
            $validated['content']['background_image'] = $path;
        }

        // Handle file upload for image blocks
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $path = $file->store('homepage/images', 'public');
            $validated['content']['image_path'] = $path;
        }

        $validated['sort_order'] = HomepageContentBlock::getNextSortOrder();
        $validated['is_active'] = $request->boolean('is_active', true);

        HomepageContentBlock::create($validated);

        return redirect()->route('homepage-content.index')
            ->with('success', 'Content block created successfully!');
    }

    /**
     * Show form to edit content block
     */
    public function edit(HomepageContentBlock $contentBlock): View
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.homepage-content.edit', compact('contentBlock', 'categories'));
    }

    /**
     * Update content block
     */
    public function update(Request $request, HomepageContentBlock $contentBlock): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:hero,text,video,image,events,html,form',
            'content' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        // Initialize content based on existing content - preserve existing data
        $content = $contentBlock->content ?: [];
        
        // Update specific content fields for each type
        switch ($validated['type']) {
            case 'hero':
                $content = array_merge($content, [
                    'show_content' => $request->has('content.show_content'),
                    'subtitle' => $validated['content']['subtitle'] ?? '',
                    'show_button' => $request->has('content.show_button'),
                    'button_text' => $validated['content']['button_text'] ?? '',
                    'button_link' => $validated['content']['button_link'] ?? '',
                ]);
                break;
            case 'text':
                $content = array_merge($content, [
                    'text' => $validated['content']['text'] ?? '',
                ]);
                break;
            case 'video':
                $content = array_merge($content, [
                    'video_url' => $validated['content']['video_url'] ?? '',
                    'description' => $validated['content']['description'] ?? '',
                ]);
                break;
            case 'image':
                $content = array_merge($content, [
                    'alt_text' => $validated['content']['alt_text'] ?? '',
                    'caption' => $validated['content']['caption'] ?? '',
                ]);
                break;
            case 'events':
                $content = array_merge($content, [
                    'limit' => (int)($validated['content']['limit'] ?? 6),
                    'show_past' => isset($validated['content']['show_past']),
                    'category_filter' => $validated['content']['category_filter'] ?? 'all',
                    'selected_categories' => $validated['content']['selected_categories'] ?? [],
                ]);
                break;
            case 'html':
                $content = array_merge($content, [
                    'html' => $validated['content']['html'] ?? '',
                    'css' => $validated['content']['css'] ?? '',
                    'description' => $validated['content']['description'] ?? '',
                ]);
                break;
            case 'form':
                $content = array_merge($content, [
                    'form_title' => $validated['content']['form_title'] ?? '',
                    'description' => $validated['content']['description'] ?? '',
                    'fields' => $validated['content']['fields'] ?? [],
                    'submit_button_text' => $validated['content']['submit_button_text'] ?? 'Submit',
                    'success_message' => $validated['content']['success_message'] ?? 'Thank you for your submission!',
                    'collect_ip' => isset($validated['content']['collect_ip']),
                ]);
                break;
        }

        // Handle file uploads based on content type
        if ($validated['type'] === 'hero' && $request->hasFile('hero_image')) {
            // Delete old hero image if exists
            if (isset($contentBlock->content['image'])) {
                Storage::disk('public')->delete($contentBlock->content['image']);
            }
            
            $file = $request->file('hero_image');
            $path = $file->store('hero-images', 'public');
            $content['image'] = $path;
            
        } elseif ($validated['type'] === 'hero') {
        }
        
        if ($validated['type'] === 'image' && $request->hasFile('image_file')) {
            // Delete old image if exists
            if (isset($contentBlock->content['image'])) {
                Storage::disk('public')->delete($contentBlock->content['image']);
            }
            
            $file = $request->file('image_file');
            $path = $file->store('content-images', 'public');
            $content['image'] = $path;
            
        } elseif ($validated['type'] === 'image') {
        }

        $contentBlock->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'content' => $content,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('homepage-content.index')
            ->with('success', 'Content block updated successfully!');
    }

    /**
     * Delete content block
     */
    public function destroy(HomepageContentBlock $contentBlock): RedirectResponse
    {
        // Delete associated files
        if (isset($contentBlock->content['image'])) {
            Storage::disk('public')->delete($contentBlock->content['image']);
        }

        $contentBlock->delete();

        return redirect()->route('homepage-content.index')
            ->with('success', 'Content block deleted successfully!');
    }

    /**
     * Update sort order via AJAX
     */
    public function updateOrder(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'blocks' => 'required|array',
                'blocks.*.id' => 'required|exists:homepage_content_blocks,id',
                'blocks.*.sort_order' => 'required|integer',
            ]);

            foreach ($validated['blocks'] as $blockData) {
                HomepageContentBlock::where('id', $blockData['id'])
                    ->update(['sort_order' => $blockData['sort_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Content block order updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update homepage content block order:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update content block order: ' . $e->getMessage()
            ], 500);
        }
    }
}