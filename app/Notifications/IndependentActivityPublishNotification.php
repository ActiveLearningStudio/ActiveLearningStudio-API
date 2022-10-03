<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Carbon\Carbon;

class IndependentActivityPublishNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $independentActivityName;

    /**
     * Create a new notification instance.
     * 
     *
     * @return void
     */
    public function __construct($userName, $independentActivityName)
    {
        $this->userName = $userName;
        $this->independentActivityName = $independentActivityName;
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ 'database'];
    }

    /*
     * G*et the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $message = "Activity [$this->independentActivityName] has been published into Microsoft Team successfully.";
        
        return [
            'message' => $message,
            'project' => $this->independentActivityName,
            
        ];
    }

    

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Return broadcast message type
     * @return string
     */
    public function broadcastType()
    {
        return  'Publish Notification';
    }
}
