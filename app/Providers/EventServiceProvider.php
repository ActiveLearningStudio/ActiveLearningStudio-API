<?php

namespace App\Providers;

use App\Events\ActivityUpdatedEvent;
use App\Events\PlaylistUpdatedEvent;
use App\Events\ProjectUpdatedEvent;
use App\Events\TeamCreatedEvent;
use App\Listeners\ActivityUpdateListener;
use App\Listeners\PlaylistUpdateListener;
use App\Listeners\ProjectUpdateListener;
use App\Listeners\TeamCreationListener;
use App\Listeners\UserRegistrationListener;
use App\Notifications\InviteToTeamNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
//            SendEmailVerificationNotification::class,
            UserRegistrationListener::class,
        ],
        TeamCreatedEvent::class => [
            TeamCreationListener::class,
        ],
        ProjectUpdatedEvent::class => [
            ProjectUpdateListener::class,
        ],
        PlaylistUpdatedEvent::class => [
            PlaylistUpdateListener::class,
        ],
        ActivityUpdatedEvent::class => [
            ActivityUpdateListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
