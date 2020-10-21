<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hello '. $this->userName . '!')
                    ->subject($this->type . ' Notification')
                    ->line($this->message .'. Please visit the studio to view it.')
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
}
