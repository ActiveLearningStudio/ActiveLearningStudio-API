<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\PrivateChannel;

class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * @var
     *
     */
    public $userId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
        Log::info('construct');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */

    public function broadcastOn()
    {
        Log::info('broadcastOn');
        return new PrivateChannel('user-channel.'.$this->userId);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */

    public function broadcastAs()
    {
        Log::info('broadcastAs');
        return 'UserEvent';
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */

    public function broadcastWith()
    {
        Log::info('broadcastWith');
        return ['message' => $this->message];
    }
}
