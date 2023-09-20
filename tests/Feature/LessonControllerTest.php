<?php

namespace Tests\Feature;

use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     */
    public function testWatchLesson()
    {
        Event::fake([
            LessonWatched::class,
        ]);

        // Create a user for authentication
        $user = User::factory()->create();

        // Simulate authentication
        $this->actingAs($user);

        // Create a lesson
        $lesson = Lesson::factory()->create();

        // Make a POST request to watch the lesson
        $response = $this->post("/watch-lesson/$lesson->id");

        // Assert the response status code
        $response->assertStatus(200);
        
        // Refresh the user instance to get the latest data from the database
        $user->refresh();

        // Assert that the lesson was watched by the user
        $this->assertTrue($user->lessons->contains($lesson));

        // Assert that the LessonWatched event was dispatched
        Event::assertDispatched(LessonWatched::class, function ($event) use ($lesson, $user) {
            return $event->lesson->id === $lesson->id && $event->user->id === $user->id;
        });
    }
}
