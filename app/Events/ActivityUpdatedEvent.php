<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityUpdatedEvent implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $playlist;
    public $activity;
    public $users;

    /**
     * Create a new event instance.
     *
     * @param $project
     * @param $playlist
     * @param $activity
     * @param $users
     */
    public function __construct($project, $playlist, $activity, $users)
    {
        $this->project = $project;
        $this->playlist = $playlist;
        $this->activity = $activity;
        $this->users = $users;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('activity-update');
    }

}
