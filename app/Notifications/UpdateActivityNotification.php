<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateActivityNotification extends Notification
{

    use Queueable;

    protected $pageUrl;

    public $sender;
    public $project;
    public $playlist;
    public $activity;

    /**
     * Create a new notification instance.
     *
     * @param $sender
     * @param $project
     * @param $playlist
     * @param $activity
     */
    public function __construct($sender, $project, $playlist, $activity)
    {
        $this->sender = $sender;
        $this->project = $project;
        $this->playlist = $playlist;
        $this->activity = $activity;
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
            ->subject('Activity Updated')
            ->line($this->sender->first_name . ' has modified activity "' . $this->activity->title . '" of playlist ' . $this->playlist->title)
            ->action('See updated activity', $this->pageUrl . '/project/' . $this->project->id . '/playlist/' . $this->playlist->id . '/activity/' . $this->activity->id . '/preview');
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
