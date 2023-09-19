<?php

namespace App\Listeners;

use App\Models\Achievement;
use Illuminate\Support\Facades\Log;

class HandleAchievementUnlocked
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
        $user = $event->user;
        $type = $event->type;

        // Retrieve the user's total comment count
        $userCommentCount = $user->comments()->count();

        // Fetch comment-related achievements from the database
        $commentAchievements = Achievement::where('type', $type)
            ->where('required_count', '<=', $userCommentCount)
            ->whereNotIn('id', $user->achievements->pluck('id'))
            ->get();

        // Attach the earned achievements to the user
        $user->achievements()->attach($commentAchievements);

        // Log the achievements unlocked
        foreach ($commentAchievements as $achievement) {
            Log::info("Achievement unlocked: " . $achievement->name);
        }

        Log::info("Comment written");
        Log::info("Event data: " . $userCommentCount);
    }
}
