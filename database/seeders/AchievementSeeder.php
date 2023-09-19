<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'First Lesson Watched',
                'required_count' => 0,
                'type' => 'Lesson',
            ],
            [
                'name' => '5 Lessons Watched',
                'required_count' => 5,
                'type' => 'Lesson',
            ],
            [
                'name' => '10 Lessons Watched',
                'required_count' => 10,
                'type' => 'Lesson',
            ],
            [
                'name' => '25 Lessons Watched',
                'required_count' => 25,
                'type' => 'Lesson',
            ],
            [
                'name' => '50 Lessons Watched',
                'required_count' => 50,
                'type' => 'Lesson',
            ],
            [
                'name' => 'First Comment Written',
                'required_count' => 0,
                'type' => 'Comment',
            ],
            [
                'name' => '3 Comments Written',
                'required_count' => 3,
                'type' => 'Comment',
            ],
            [
                'name' => '5 Comments Written',
                'required_count' => 5,
                'type' => 'Comment',
            ],
            [
                'name' => '10 Comments Written',
                'required_count' => 10,
                'type' => 'Comment',
            ],
            [
                'name' => '20 Comments Written',
                'required_count' => 20,
                'type' => 'Comment',
            ],
        ];

        Achievement::factory()->createMany($data);
    }
}
