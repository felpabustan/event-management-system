<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Laravel Workshop: Building Modern Web Applications',
                'date' => now()->addDays(7)->format('Y-m-d'),
                'time' => '09:00',
                'venue' => 'Tech Conference Center, Room A',
                'description' => 'Join us for a comprehensive workshop on Laravel framework. Learn the latest features, best practices, and build a complete web application from scratch. Perfect for beginners and intermediate developers.',
                'max_capacity' => 50,
                'current_capacity' => 0,
            ],
            [
                'title' => 'Digital Marketing Strategies 2025',
                'date' => now()->addDays(14)->format('Y-m-d'),
                'time' => '14:00',
                'venue' => 'Downtown Business Hub, Conference Hall',
                'description' => 'Discover the latest digital marketing trends and strategies for 2025. Learn about social media marketing, SEO, content marketing, and analytics. Network with industry professionals.',
                'max_capacity' => 100,
                'current_capacity' => 0,
            ],
            [
                'title' => 'Startup Pitch Competition',
                'date' => now()->addDays(21)->format('Y-m-d'),
                'time' => '18:00',
                'venue' => 'Innovation Center, Main Auditorium',
                'description' => 'Watch promising startups pitch their ideas to a panel of experienced investors. Great networking opportunity for entrepreneurs, investors, and business enthusiasts.',
                'max_capacity' => 200,
                'current_capacity' => 0,
            ],
            [
                'title' => 'Photography Masterclass: Portrait Techniques',
                'date' => now()->addDays(28)->format('Y-m-d'),
                'time' => '10:00',
                'venue' => 'Art Studio Gallery, Workshop Room',
                'description' => 'Master the art of portrait photography with professional photographer Jane Smith. Learn lighting techniques, composition, and post-processing. Bring your camera!',
                'max_capacity' => 20,
                'current_capacity' => 0,
            ],
            [
                'title' => 'Cybersecurity Awareness Seminar',
                'date' => now()->addDays(35)->format('Y-m-d'),
                'time' => '13:30',
                'venue' => 'University Campus, Lecture Hall B',
                'description' => 'Learn about the latest cybersecurity threats and how to protect yourself and your business. Topics include password security, phishing attacks, and data protection.',
                'max_capacity' => 150,
                'current_capacity' => 0,
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }
    }
}
