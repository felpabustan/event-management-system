<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageContentBlock;

class HomepageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a hero section
        HomepageContentBlock::create([
            'title' => 'Discover Amazing Events',
            'type' => 'hero',
            'content' => [
                'subtitle' => 'Near You',
                'button_text' => 'Browse Events',
                'button_link' => '#events'
            ],
            'sort_order' => 1,
            'is_active' => true
        ]);

        // Create a text content block
        HomepageContentBlock::create([
            'title' => 'About Our Events',
            'type' => 'text',
            'content' => [
                'text' => 'Join thousands of attendees at our carefully curated events. From workshops and conferences to networking sessions and masterclasses - find your next learning adventure. Our events are designed to inspire, educate, and connect professionals from all industries.'
            ],
            'sort_order' => 2,
            'is_active' => true
        ]);

        // Create an events list
        HomepageContentBlock::create([
            'title' => 'Upcoming Events',
            'type' => 'events',
            'content' => [
                'limit' => 6,
                'show_past' => false
            ],
            'sort_order' => 3,
            'is_active' => true
        ]);
    }
}