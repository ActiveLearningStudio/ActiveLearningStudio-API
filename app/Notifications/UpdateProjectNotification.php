<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateProjectNotification extends Notification
{

    use Queueable;

    protected $pageUrl;

    public $sender;
    public $project;
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param $sender
     * @param $project
     */
    public function __construct($sender, $project)
    {
        $this->sender = $sender;
        $this->project = $project;
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
            ->subject('Project Updated')
            ->line($this->sender->first_name . ' has modified team project ' . $this->project->name)
            ->action('See updated project', $this->pageUrl . '/project/' . $this->project->id . '/preview');
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
