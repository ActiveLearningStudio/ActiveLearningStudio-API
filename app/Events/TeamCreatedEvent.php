<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TeamCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $team;
    public $projects;
    public $users;

    /**
     * Create a new event instance.
     *
     * @param $team
     * @param $projects
     * @param $users
     */
    public function __construct($team, $projects, $users)
    {
        $this->team = $team;
        $this->projects = $projects;
        $this->users = $users;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
