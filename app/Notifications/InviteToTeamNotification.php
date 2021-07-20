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
    public $note;

    /**
     * Create a new notification instance.
     *
     * @param $sender
     * @param $team
     * @param string $note
     */
    public function __construct($sender, $team, $note = '')
    {
        $this->sender = $sender;
        $this->team = $team;
        $this->note = $note;
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
        return (new MailMessage)
            ->subject('Invited to the Team')
            ->line($this->sender->first_name . ' has invited you in the ' . $this->team->name . ' team')
            ->line($this->note)
            ->action('View the Team', $this->pageUrl . '/org/' . $this->team->organization->domain . '/teams/' . $this->team->id);
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
