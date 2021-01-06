<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrganizationInvite extends Notification
{
    use Queueable;

    protected $pageUrl;

    public $sender;
    public $organization;
    public $token;
    public $note;

    /**
     * Create a new notification instance.
     *
     * @param $sender
     * @param $organization
     * @param $token
     * @param string $note
     * @return void
     */
    public function __construct($sender, $organization, $token, $note = '')
    {
        $this->sender = $sender;
        $this->organization = $organization;
        $this->token = $token;
        $this->note = $note;
        $this->pageUrl = config('app.front_end_url');
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
        return (new MailMessage)
            ->subject('Invite to the Organization')
            ->line($this->sender->first_name . ' has invited you to join the organization ' . $this->organization->name)
            ->line($this->note)
            ->action('Join the Organization', $this->pageUrl);
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
