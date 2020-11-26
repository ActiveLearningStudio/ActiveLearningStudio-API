<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlaylistUpdatedEvent implements ShouldBroadcastNow
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $playlist;

    /**
     * Create a new event instance.
     *
     * @param $project
     * @param $playlist
     */
    public function __construct($project, $playlist)
    {
        $this->project = $project;
        $this->playlist = $playlist;
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

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $authenticated_user = auth()->user();

        return [
            'userId' => $authenticated_user->id,
            'projectId' => $this->project->id,
            'playlistId' => $this->playlist->id,
        ];
    }

}
