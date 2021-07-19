<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $group;
    public $projects;
    public $users;

    /**
     * Create a new event instance.
     *
     * @param $group
     * @param $projects
     * @param $users
     */
    public function __construct($group, $projects, $users)
    {
        $this->group = $group;
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
