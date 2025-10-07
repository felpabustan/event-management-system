<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Workshops',
                'description' => 'Hands-on learning sessions and skill-building workshops',
                'max_registrations_per_user' => 2,
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Panels',
                'description' => 'Expert panel discussions and Q&A sessions',
                'max_registrations_per_user' => 1,
                'color' => '#10B981',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']], 
                $categoryData
            );
        }
    }
}