<?php

namespace App\Listeners;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleBadgeUnlocked
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $payloadUser = $event->user;

        // Fetching the user again so we get the latest accurate result
        $user = User::find($payloadUser->id);
        $userAchievements = count($user->achievements);

        Log::info("Achievements $userAchievements");
        $eligibleBadge = Badge::where('achievements_required', '=', $userAchievements)->first();

        if ($eligibleBadge) {
            // Detach any existing badge
            if ($user->badge) {
                $user->badge_id = null;
                $user->save();
            }
        
            // Attach the new/highest earned badge
            $user->badge()->associate($eligibleBadge)->save();
            Log::info("Badge unlocked: " . $eligibleBadge->name);
        } else {
            Log::info("No eligible badge found.");
        }
    }
}
