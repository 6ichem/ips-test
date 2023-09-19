<?php

namespace App\Listeners;

use App\Events\CommentWritten;
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
        $commentId = $event->comment->id;
        $userId = $event->comment->user_id;

        $findComment = Comment::findOrFail($commentId);
        $userComments = $findComment->getUserComments($userId);

        Log::info("Comment written");
        Log::info("Event data: " . count($userComments));
    }
}
