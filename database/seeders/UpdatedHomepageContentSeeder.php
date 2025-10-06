<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageContentBlock;

class UpdatedHomepageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing content blocks
        HomepageContentBlock::truncate();

        // Hero Section with toggles - full featured
        HomepageContentBlock::create([
            'type' => 'hero',
            'title' => 'Welcome to Our Event Management System',
            'content' => [
                'show_content' => true,
                'subtitle' => 'Register for exciting events happening in your area',
                'show_button' => true,
                'button_text' => 'View All Events',
                'button_link' => '#events',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Hero Section - image only (no text or buttons)
        HomepageContentBlock::create([
            'type' => 'hero',
            'title' => 'Pure Hero Image',
            'content' => [
                'show_content' => false,
                'subtitle' => '',
                'show_button' => false,
                'button_text' => '',
                'button_link' => '',
            ],
            'sort_order' => 2,
            'is_active' => false, // Disabled by default for demo
        ]);

        // Text Content Block
        HomepageContentBlock::create([
            'type' => 'text',
            'title' => 'About Our Events',
            'content' => [
                'text' => 'Join us for exciting events, workshops, and networking opportunities. Our events are designed to bring together like-minded individuals and create meaningful connections.',
            ],
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Image Content Block
        HomepageContentBlock::create([
            'type' => 'image',
            'title' => 'Event Highlights',
            'content' => [
                'alt_text' => 'Event highlights and memorable moments',
                'caption' => 'Capturing the best moments from our recent events',
            ],
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Video Content Block
        HomepageContentBlock::create([
            'type' => 'video',
            'title' => 'See What Our Events Are Like',
            'content' => [
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'Watch this video to get a taste of the amazing events we organize.',
            ],
            'sort_order' => 5,
            'is_active' => true,
        ]);

        // Events List Content Block
        HomepageContentBlock::create([
            'type' => 'events',
            'title' => 'Upcoming Events',
            'content' => [
                'limit' => 6,
                'show_past' => false,
            ],
            'sort_order' => 6,
            'is_active' => true,
        ]);
    }
}