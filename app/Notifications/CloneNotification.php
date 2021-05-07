<?php

namespace App\Notifications;

use App\Http\Resources\V1\UserResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Carbon\Carbon;

class CloneNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public $message;
    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $type;

    /**
     * CloneNotification constructor.
     * @param $message
     * @param $type
     * @param $userName
     */
    public function __construct($message, $type, $userName)
    {
        $this->message = $message;
        $this->type = ucfirst($type);
        $this->userName = $userName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hello '. $this->userName . '!')
                    ->subject($this->type . ' Notification')
                    ->line($this->message . '. Please visit the studio to view it.')
                    //->action('Notification Action', url('/'))
                    ->line('Thank you for your patience!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        $timestamp = Carbon::parse(now()->addSecond()->toDateTimeString());
        return new BroadcastMessage(array(
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'data' => $this->toDatabase($notifiable),
            'notifiable' => $notifiable,
            'read_at' => null,
            'created_at' => $timestamp->diffForHumans(),
            'updated_at' => $timestamp->diffForHumans(),
        ));
    }

    /**
     * Return broadcast message type
     * @return string
     */
    public function broadcastType()
    {
        return $this->type . ' Notification';
    }

}
