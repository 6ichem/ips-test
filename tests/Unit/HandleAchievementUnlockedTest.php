<?php

namespace Tests\Unit;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Listeners\HandleAchievementUnlocked;
use App\Models\User;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\Lesson;
use Exception;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class HandleAchievementUnlockedTest extends TestCase
{
    use RefreshDatabase;

    public function testValidLessonAchievementUnlock()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $lessonAchievement  = Achievement::factory()->create([
            'name' => "Achievement Example",
            'type' => 'Lesson',
            'required_count' => 1,
        ]);

        $user->lessons()->attach($lesson, ['watched' => true]);

        // Create an AchievementUnlocked event with the type
        $event = new AchievementUnlocked($user, 'Lesson');

        // Fake the BadgeUnlocked event
        Event::fake([BadgeUnlocked::class]);

        $listener = new HandleAchievementUnlocked();
        // $this->assertNull($user->achievements->first());

        $listener->handle($event);

        $user->refresh();

        $this->assertCount(1, $user->achievements);
        $this->assertEquals($lessonAchievement->id, $user->achievements->first()->id);
        Event::assertDispatched(BadgeUnlocked::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }
     

    public function testInvalidAchievementType()
    {
        $this->expectException(Exception::class);

        $user = User::factory()->create();

        $event = new AchievementUnlocked($user, 'InvalidType');
        $listener = new HandleAchievementUnlocked();

        $user->refresh();

        $listener->handle($event);
    }

    public function testValidCommentAchievementUnlock()
    {
        $user = User::factory()->create();
        $commentAchievement = Achievement::factory()->create([
            'name' => "Achievement Example",
            'type' => 'Comment',
            'required_count' => 5,
        ]);

        // Simulate creating 10 comments
        Comment::factory()->count(10)->create(['user_id' => $user->id]);

        // Create an AchievementUnlocked event with the type
        $event = new AchievementUnlocked($user, 'Comment');

        // Fake the BadgeUnlocked event
        Event::fake([BadgeUnlocked::class]);

        $listener = new HandleAchievementUnlocked();

        $this->assertNull($user->achievements->first());

        $listener->handle($event);

        $user->refresh();

        $this->assertCount(1, $user->achievements);
        $this->assertEquals($commentAchievement->id, $user->achievements->first()->id);
        Event::assertDispatched(BadgeUnlocked::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }
}
