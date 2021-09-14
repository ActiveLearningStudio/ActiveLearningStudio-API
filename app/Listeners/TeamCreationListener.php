<?php

namespace App\Listeners;

use App\Events\TeamCreatedEvent;
use App\Notifications\InviteToTeamNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TeamCreationListener
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
     * @param TeamCreatedEvent $event
     * @return void
     */
    public function handle(TeamCreatedEvent $event)
    {
        $auth_user = auth()->user();
        foreach ($event->users as $user) {
            $user['user']->notify(new InviteToTeamNotification($auth_user, $event->team, $user['note']));
        }
    }
}
