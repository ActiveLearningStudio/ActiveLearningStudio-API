<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class InviteToGroupNotification extends Notification
{
    use Queueable;

    protected $pageUrl;

    public $sender;
    public $group;
    public $token;
    public $note;

    /**
     * Create a new notification instance.
     *
     * @param $sender
     * @param $group
     * @param $token
     * @param string $note
     */
    public function __construct($sender, $group, $token, $note = '')
    {
        $this->sender = $sender;
        $this->group = $group;
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
        return (new MailMessage)
            ->subject('Invited to the Group')
            ->line($this->sender->first_name . ' has invited you in the group ' . $this->group->name . ' group')
            ->line($this->note)
            ->action('View the Group', $this->pageUrl . '/org/' . $this->group->organization->domain . '/groups/' . $this->group->id);
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
