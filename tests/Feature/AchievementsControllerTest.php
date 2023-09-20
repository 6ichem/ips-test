<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Achievement;
use App\Models\Badge;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;

class AchievementsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testIndexRouteReturnsJsonResponse()
    {
        $user = User::factory()->create();

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
    }

    public function testIndexRouteReturnsCorrectDataForNewUser()
    {
        $user = User::factory()->create();

        $response = $this->get("/users/{$user->id}/achievements");

        $unlockedAchievements = collect([]);
        $nextAvailableAchievements = collect(Achievement::whereNotIn('id', $unlockedAchievements->pluck('id'))->get())->toArray();
        $currentBadge = $user->badge->name ?? '';
        $nextBadge = Badge::where('achievements_required', '>', $user->achievements->count())->orderBy('achievements_required')->first()->name ?? '';
        $remainingToUnlockNextBadge = ($nextBadge !== '') ? $user->achievements->count() : 0; 

        $response->assertStatus(200)
            ->assertJson([
                'unlocked_achievements' => $unlockedAchievements->toArray(),
                'next_available_achievements' => $nextAvailableAchievements,
                'current_badge' => $currentBadge,
                'next_badge' => $nextBadge,
                'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
            ]);
    }

    public function testIndexRouteReturnsCorrectDataForUserWithAchievements()
    {
        $user = User::factory()->create();
        $achievements = Achievement::all();
        $user->achievements()->attach($achievements->take(2));

        $response = $this->get("/users/{$user->id}/achievements");

        $unlockedAchievements = collect($user->achievements);

        $nextAvailableAchievements = collect(Achievement::whereNotIn('id', $unlockedAchievements->pluck('id'))->get())->toArray();
        $currentBadge = $user->badge->name ?? '';
        $nextBadge = Badge::where('achievements_required', '>', $user->achievements->count())->orderBy('achievements_required')->first()->name ?? '';
        $remainingToUnlockNextBadge = ($nextBadge !== '') ? $user->achievements->count() : 0; 

        $response->assertStatus(200)
            ->assertJson([
                'unlocked_achievements' => $unlockedAchievements->toArray(),
                'next_available_achievements' => $nextAvailableAchievements,
                'current_badge' => $currentBadge,
                'next_badge' => $nextBadge,
                'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
            ]);
    }

    public function testIndexRouteReturnsCorrectDataForUserWithNextBadge()
    {
        $user = User::factory()->create();
        $achievements = Achievement::all();
        $user->achievements()->attach($achievements->take(4));
        
        $response = $this->get("/users/{$user->id}/achievements");

        $unlockedAchievements = collect($user->achievements);

        $nextAvailableAchievements = collect(Achievement::whereNotIn('id', $unlockedAchievements->pluck('id'))->get())->toArray();
        $currentBadge = $user->badge->name ?? '';
        $nextBadge = Badge::where('achievements_required', '>', $user->achievements->count())->orderBy('achievements_required')->first()->name ?? '';
        $remainingToUnlockNextBadge = ($nextBadge !== '') ? $user->achievements->count() : 0; 

        $response->assertStatus(200)
            ->assertJson([
                'unlocked_achievements' => $unlockedAchievements->toArray(),
                'next_available_achievements' => $nextAvailableAchievements,
                'current_badge' => $currentBadge,
                'next_badge' => $nextBadge,
                'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
            ]);
    }
}
