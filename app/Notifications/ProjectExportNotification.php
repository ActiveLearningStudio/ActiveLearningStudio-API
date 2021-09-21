<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Carbon\Carbon;

class ProjectExportNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $projectName;

    /**
     * Create a new notification instance.
     * 
     *
     * @return void
     */
    public function __construct($path, $userName, $projectName)
    {
        $this->path = basename($path);
        $this->userName = $userName;
        $this->projectName = $projectName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hello '. $this->userName . '!')
                    ->line('Your export has been completed')
                    ->action('Download Project',url(Storage::url('exports/'.basename($this->path))))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $file_path = url(Storage::url('exports/' . basename($this->path)));
        return [
            'message' => "Project[$this->projectName] has been exported successfully.Please <a href='$file_path' target='_blank'>Click Here</a> to download the exported file",
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        $timestamp = Carbon::parse(now()->addSecond()->toDateTimeString());
        return new BroadcastMessage(array(
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'data' => $this->toDatabase($notifiable),
            'notifiable' => $notifiable,
            'read_at' => null,
            'created_at' => $timestamp->diffForHumans(),
            'updated_at' => $timestamp->diffForHumans(),
            'file_path'  => url(Storage::url('exports/' . basename($this->path))),
        ));
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

    /**
     * Return broadcast message type
     * @return string
     */
    public function broadcastType()
    {
        return  'Export Notification';
    }
}
