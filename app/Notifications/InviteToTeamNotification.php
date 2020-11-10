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

    public $sender;
    public $team;
    public $token;
    public $note;

    /**
     * Create a new notification instance.
     *
     * @param $sender
     * @param $team
     * @param $token
     * @param $note
     */
    public function __construct($sender, $team, $token, $note)
    {
        $this->sender = $sender;
        $this->team = $team;
        $this->token = $token;
        $this->note = $note;
        // $this->pageUrl = config('app.front_end_url') . '/teams/invite';
        $this->pageUrl = config('app.front_end_url');
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
            ->subject('Invite to the Team')
            ->line($this->sender->first_name . ' has invited you to join the team ' . $this->team->name)
            ->line($this->note)
            // ->action('Join the Team', $this->pageUrl . '?token=' . $this->token);
            ->action('Join the Team', $this->pageUrl . '/teams/' . $this->team->id . '/projects');
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
