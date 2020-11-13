<?php

namespace App\Listeners;

use App\Events\ActivityUpdatedEvent;
use App\Notifications\UpdateActivityNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ActivityUpdateListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ActivityUpdatedEvent $event
     * @return void
     */
    public function handle(ActivityUpdatedEvent $event)
    {
//        $auth_user = auth()->user();
//        foreach ($event->users as $user) {
//            $user->notify(new UpdateActivityNotification($auth_user, $event->project, $event->playlist, $event->activity));
//        }
    }

}
