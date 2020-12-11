<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityUpdatedEvent implements ShouldBroadcastNow
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $playlist;
    public $activity;

    /**
     * Create a new event instance.
     *
     * @param $project
     * @param $playlist
     * @param $activity
     */
    public function __construct($project, $playlist, $activity)
    {
        $this->project = $project;
        $this->playlist = $playlist;
        $this->activity = $activity;
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
            'activityId' => $this->activity->id,
        ];
    }

}
