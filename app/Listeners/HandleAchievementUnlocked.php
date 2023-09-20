<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use Exception;
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
        // We have access to all the achievement info
        // We have access to all the user data

        $user = $event->user;
        $type = $event->type;
        
        if ($type !== "Lesson" && $type !== "Comment") {
            throw new Exception("Invalid achievement type");
        }

        $totalCount = null;

        switch ($type) {
            case "Lesson":
                $totalCount = $user->watched()->count();
                break;
            case "Comment":
                $totalCount = $user->comments()->count();
                break;
        }

        // Fetch type-related achievements from the database
        $commentAchievements = Achievement::where('type', $type)
            ->where('required_count', '<=', $totalCount)
            ->whereNotIn('id', $user->achievements->pluck('id'))
            ->get();

        // Attach the earned achievements to the user
        $user->achievements()->attach($commentAchievements);

        // Log the achievements unlocked
        foreach ($commentAchievements as $achievement) {
            Log::info("Achievement unlocked: " . $achievement->name);
        }

        Log::info("totalCount: " . $totalCount);
        
        BadgeUnlocked::dispatch($user);
    }
}
