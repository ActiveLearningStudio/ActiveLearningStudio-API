<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlaylistUpdatedEvent implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $playlist;
    public $users;

    /**
     * Create a new event instance.
     *
     * @param $project
     * @param $playlist
     * @param $users
     */
    public function __construct($project, $playlist, $users)
    {
        $this->project = $project;
        $this->playlist = $playlist;
        $this->users = $users;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('playlist-update');
    }

}
