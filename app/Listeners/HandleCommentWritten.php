<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleCommentWritten
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
    public function handle(CommentWritten $event): void
    {
        $comment = $event->comment;
        $user = User::find($comment->user_id);

        // Retrieve the user's total comment count
        $userCommentCount = $user->comments()->count();

        // Fetch comment-related achievements from the database
        $commentAchievements = Achievement::where('type', 'Comment')
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
