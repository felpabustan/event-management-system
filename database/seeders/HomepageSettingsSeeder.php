<?php

namespace Database\Seeders;

use App\Models\HomepageSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomepageSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Hero Section
            [
                'key' => 'hero_title',
                'value' => 'Discover Amazing',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Hero Title',
                'description' => 'Main title displayed in the hero section',
                'sort_order' => 1,
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Events Near You',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Hero Subtitle',
                'description' => 'Subtitle text (appears in yellow)',
                'sort_order' => 2,
            ],
            [
                'key' => 'hero_description',
                'value' => 'Join thousands of attendees at our carefully curated events. From workshops and conferences to networking sessions and masterclasses - find your next learning adventure.',
                'type' => 'textarea',
                'group' => 'hero',
                'label' => 'Hero Description',
                'description' => 'Description text below the title',
                'sort_order' => 3,
            ],
            [
                'key' => 'hero_button_text',
                'value' => 'Browse Events',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Hero Button Text',
                'description' => 'Text for the main call-to-action button',
                'sort_order' => 4,
            ],
            
            // Events Section
            [
                'key' => 'events_section_title',
                'value' => 'Upcoming Events',
                'type' => 'text',
                'group' => 'events',
                'label' => 'Events Section Title',
                'description' => 'Title for the events listing section',
                'sort_order' => 1,
            ],
            [
                'key' => 'events_section_subtitle',
                'value' => 'Register for exciting events happening soon!',
                'type' => 'text',
                'group' => 'events',
                'label' => 'Events Section Subtitle',
                'description' => 'Subtitle for the events listing section',
                'sort_order' => 2,
            ],
            
            // Colors
            [
                'key' => 'hero_gradient_from',
                'value' => 'blue-600',
                'type' => 'select',
                'group' => 'colors',
                'label' => 'Hero Gradient Start',
                'description' => 'Starting color for hero background gradient',
                'sort_order' => 1,
            ],
            [
                'key' => 'hero_gradient_to',
                'value' => 'purple-700',
                'type' => 'select',
                'group' => 'colors',
                'label' => 'Hero Gradient End',
                'description' => 'Ending color for hero background gradient',
                'sort_order' => 2,
            ],
            
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Event Management System',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Name',
                'description' => 'Name displayed in header and title',
                'sort_order' => 1,
            ],
        ];

        foreach ($settings as $setting) {
            HomepageSetting::create($setting);
        }
    }
}