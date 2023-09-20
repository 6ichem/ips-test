<?php

namespace Tests\Unit;

use App\Events\BadgeUnlocked;
use App\Listeners\HandleBadgeUnlocked;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class HandleBadgeUnlockedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_handles_badge_unlocked_event()
    {
        // Create a test user with some achievements
        $user = User::factory()->create();
        $achievements  = Achievement::factory()->count(5)->create();

        Log::info("Test achievements " . $achievements);

        $user->achievements()->createMany($achievements->toArray());

        // Create a badge with achievements_required matching the user's achievements
        $badge = Badge::factory()->create([
            'name' => 'Test Badge',
            'achievements_required' => $user->achievements->count(),
        ]);

        // Trigger the event listener
        $event = new BadgeUnlocked($user, );
        $listener = new HandleBadgeUnlocked();
        $listener->handle($event);

        // Assert that the user now has the badge attached
        $user->refresh();
        $this->assertEquals($badge->id, $user->badge_id);
    }
}
