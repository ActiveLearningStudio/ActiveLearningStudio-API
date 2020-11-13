<?php

namespace App\Listeners;

use App\Events\ProjectUpdatedEvent;
use App\Notifications\UpdateProjectNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProjectUpdateListener
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
     * @param ProjectUpdatedEvent $event
     * @return void
     */
    public function handle(ProjectUpdatedEvent $event)
    {
//        $auth_user = auth()->user();
//        foreach ($event->users as $user) {
//            $user->notify(new UpdateProjectNotification($auth_user, $event->project));
//        }
    }

}
