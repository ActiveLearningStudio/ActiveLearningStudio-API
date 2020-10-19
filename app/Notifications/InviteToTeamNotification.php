<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class InviteToTeamNotification extends Notification
{
    use Queueable;

    protected $pageUrl;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->pageUrl = config('app.front_end_url') . '/teams/invite';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        // if (static::$toMailCallback) {
        //     return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        // }

        return (new MailMessage)
            ->subject('Invite to the Team')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Invite Team', $this->pageUrl . '?token=' . $this->token);
            // ->line('This invitation link will expire in ' . config('auth.passwords.users.expire') . ' minutes.')
            // ->line('If you did not request a password reset, no further action is required.');
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
}
