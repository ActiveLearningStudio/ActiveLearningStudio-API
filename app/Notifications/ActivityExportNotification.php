<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Carbon\Carbon;

class ActivityExportNotification extends Notification
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
    public $activityTitle;

    /**
     * @var string
     */
    public $organizationId;

    /**
     * Create a new notification instance.
     * 
     *
     * @return void
     */
    public function __construct($path, $userName, $activityTitle, $organizationId)
    {
        $this->path = basename($path);
        $this->userName = $userName;
        $this->activityTitle = $activityTitle;
        $this->organizationId = $organizationId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ 'database', 'broadcast'];
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
                    ->action('Download Activty',url(Storage::url('exports/'.basename($this->path))))
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
        \Log::info($this->id);
        $message = "Activity [$this->activityTitle] has been exported successfully. 
                    Please <a href='$file_path' target='_blank'>Click Here</a> to download the exported file";
                    
        \DB::table('notifications')
                    ->where('id', $this->id)
                    ->update(['organization_id' => $this->organizationId]);
        return [
            'message' => $message,
            'project' => $this->activityTitle,
            'link' => $file_path,
            'file_name' => basename($this->path),
            'organization_id' => $this->organizationId,
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
