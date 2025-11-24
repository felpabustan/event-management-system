<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomepageContentBlock extends Model
{
    protected $fillable = [
        'type',
        'title',
        'content',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get all active content blocks ordered by sort_order
     */
    public static function getActiveBlocks()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get content blocks for admin management
     */
    public static function getAllBlocks()
    {
        return static::orderBy('sort_order')->get();
    }

    /**
     * Get the next sort order
     */
    public static function getNextSortOrder(): int
    {
        return (static::max('sort_order') ?? 0) + 1;
    }

    /**
     * Get hero image URL if available
     */
    public function getHeroImageUrl(): ?string
    {
        if ($this->type === 'hero' && isset($this->content['image'])) {
            return Storage::url($this->content['image']);
        }
        return null;
    }

    /**
     * Get image block URL if available
     */
    public function getImageUrl(): ?string
    {
        if ($this->type === 'image' && isset($this->content['image'])) {
            return Storage::url($this->content['image']);
        }
        return null;
    }

    /**
     * Get video embed code
     */
    public function getVideoEmbedUrl(): ?string
    {
        if ($this->type === 'video' && isset($this->content['video_url'])) {
            $url = $this->content['video_url'];
            
            // Convert YouTube URL to embed format
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
                return "https://www.youtube.com/embed/{$matches[1]}";
            }
            
            // Convert Vimeo URL to embed format
            if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
                return "https://player.vimeo.com/video/{$matches[1]}";
            }
        }
        return null;
    }

    /**
     * Get HTML content for display
     */
    public function getHtmlContent(): ?string
    {
        if ($this->type === 'html' && isset($this->content['html'])) {
            return $this->content['html'];
        }
        return null;
    }

    /**
     * Get block type display name
     */
    public function getTypeDisplayName(): string
    {
        return match($this->type) {
            'hero' => 'Hero Section',
            'text' => 'Text Content',
            'video' => 'Video Embed',
            'image' => 'Image Block',
            'events' => 'Events Listing',
            'html' => 'Custom HTML',
            'form' => 'Form Block',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get form submission record for this block
     */
    public function formSubmission()
    {
        return $this->hasOne(FormSubmission::class, 'content_block_id');
    }

    /**
     * Get form configuration
     */
    public function getFormConfig(): ?array
    {
        if ($this->type === 'form' && isset($this->content['fields'])) {
            return $this->content;
        }
        return null;
    }

    /**
     * Get filtered events for events blocks
     */
    public function getFilteredEvents()
    {
        if ($this->type !== 'events') {
            return collect();
        }

        $query = \App\Models\Event::query();

        // Apply category filtering
        $categoryFilter = $this->content['category_filter'] ?? 'all';
        $selectedCategories = $this->content['selected_categories'] ?? [];

        if ($categoryFilter === 'specific' && !empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
        } elseif ($categoryFilter === 'exclude' && !empty($selectedCategories)) {
            $query->whereNotIn('category_id', $selectedCategories);
        }

        // Filter by date unless show_past is enabled
        $showPast = $this->content['show_past'] ?? false;
        if (!$showPast) {
            $query->where('date', '>=', now()->startOfDay());
        }

        // Apply limit
        $limit = $this->content['limit'] ?? 6;
        
        return $query->with(['category'])
            ->orderBy('date')
            ->limit($limit)
            ->get();
    }
}