<?php

namespace App\Listeners;

use App\Events\GroupCreatedEvent;
use App\Notifications\InviteToGroupNotification;

class GroupCreationListener
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
     * @param GroupCreatedEvent $event
     * @return void
     */
    public function handle(GroupCreatedEvent $event)
    {
        $auth_user = auth()->user();
        foreach ($event->users as $user) {
            $user['user']->notify(new InviteToGroupNotification($auth_user, $event->group, $user['user']->token, $user['note']));
        }
    }
}
