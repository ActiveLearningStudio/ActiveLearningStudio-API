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
    public $page;
    public $note;
    public $user_email;

    /**
     * Create a new notification instance.
     *
     * @param $sender
     * @param $organization
     * @param $page
     * @param string $note
     * @param string $user_email
     * @return void
     */
    public function __construct($sender, $organization, $page, $note = '', $user_email)
    {
        $this->sender = $sender;
        $this->organization = $organization;
        $this->page = $page;
        $this->note = $note;
        $this->user_email = $user_email;
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
        if ($this->page === 'register') {
            $url = $this->pageUrl . '/' . $this->page . '/' . $this->organization->domain . '?email=' . $this->user_email;
            $subject = 'Invitation to join the organization';
            $caption = 'Join the Organization';
        } else {
            $url = $this->pageUrl . '/' . $this->page . '/' . $this->organization->domain;
            $subject = 'Login to organization';
            $caption = 'Login to view Organization';
        }

        return (new MailMessage)
            ->subject($subject)
            ->line($this->sender->first_name . ' has invited you to join the organization ' . $this->organization->name)
            ->line($this->note)
            ->action($caption, $url);
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
