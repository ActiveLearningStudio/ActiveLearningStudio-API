<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdatePlaylistNotification extends Notification
{

    use Queueable;

    protected $pageUrl;

    public $sender;
    public $project;
    public $playlist;

    /**
     * Create a new notification instance.
     *
     * @param $sender
     * @param $project
     * @param $playlist
     */
    public function __construct($sender, $project, $playlist)
    {
        $this->sender = $sender;
        $this->project = $project;
        $this->playlist = $playlist;
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
            ->subject('Playlist Updated')
            ->line($this->sender->first_name . ' has modified playlist "' . $this->playlist->title . '" of team project ' . $this->project->name)
            ->action('See updated playlist', $this->pageUrl . '/project/' . $this->project->id . '/playlist/' . $this->playlist->id . '/preview');
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
