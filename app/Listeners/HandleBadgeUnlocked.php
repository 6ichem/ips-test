<?php

namespace App\Listeners;

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
        $user = $event->user;
        $badge = $event->badge;

        // Attach the badge to the user
        $user->badges()->attach($badge);

        // Log the badge unlocked
        Log::info("Badge unlocked: " . $badge->name);
    }
}
