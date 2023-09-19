<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Beginner',
                'achievements_required' => 0,
            ],
            [
                'name' => 'Intermediate',
                'achievements_required' => 4,
            ],
            [
                'name' => 'Advanced',
                'achievements_required' => 8,
            ],
            [
                'name' => 'Master',
                'achievements_required' => 10,
            ],
        ];
    
        Badge::factory()->createMany($badges);
    }    
}
