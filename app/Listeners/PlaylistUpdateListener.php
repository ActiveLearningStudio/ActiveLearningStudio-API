<?php

namespace App\Listeners;

use App\Events\PlaylistUpdatedEvent;
use App\Notifications\UpdatePlaylistNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PlaylistUpdateListener
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
     * @param PlaylistUpdatedEvent $event
     * @return void
     */
    public function handle(PlaylistUpdatedEvent $event)
    {
//        $auth_user = auth()->user();
//        foreach ($event->users as $user) {
//            $user->notify(new UpdatePlaylistNotification($auth_user, $event->project, $event->playlist));
//        }
    }

}
