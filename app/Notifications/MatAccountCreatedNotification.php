<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class MatAccountCreatedNotification extends Notification
{
    use Queueable;

    protected $pageUrl;

    public $team;
    public $password;

    /**
     * Create a new notification instance.
     *
     * @param $team
     * @param $password
     */
    public function __construct($team, $password)
    {
        $this->team = $team;
        $this->password = $password;
        $this->pageUrl = 'https://' . config('mattermost.host') . '/' . $team['name'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        // if (static::$toMailCallback) {
        //     return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        // }

        return (new MailMessage)
            ->subject('Mattermost Account Created')
            ->line('You mattermost account has been created with your email by CurrikiStudio.')
            ->line('Your temporary password is ' . $this->password . '. Please reset it at first login.')
            ->action('View Channel', $this->pageUrl);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
