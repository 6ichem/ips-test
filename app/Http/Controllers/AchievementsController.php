<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $currentUser = $user; // Retrieve the user sent in the request

        // Retrieve the user's unlocked achievements
        $unlockedAchievements = $currentUser->achievements;

        // Retrieve next available achievements
        $nextAvailableAchievements = Achievement::whereNotIn('id', $unlockedAchievements->pluck('id'))->get();

        // Retrieve the user's current badge name (if any)
        $currentBadge = $currentUser->badge->name ?? '';

        // Retrieve the name of the next badge
        $nextBadge = Badge::where('achievements_required', '>', $currentUser->achievements->count())->orderBy('achievements_required')->first()->name ?? '';

        // Calculate the remaining achievements needed to unlock the next badge
        $remainingToUnlockNextBadge = ($nextBadge !== '') ? $currentUser->achievements->count() : 0; 

        return response()->json([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
        ]);
    
    }
}
