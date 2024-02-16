<?php

namespace App\Notifications\C2E\Publisher;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Carbon\Carbon;

class ActivityPublishNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public $userName;

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
    public function __construct($userName, $activityTitle, $organizationId)
    {
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
                    ->line('Your request to publish activity [' . $this->activityTitle . '] has been completed')
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
        \Log::info($this->id);
        $message = "Activity [$this->activityTitle] has been published successfully.";
                    
        \DB::table('notifications')
                    ->where('id', $this->id)
                    ->update(['organization_id' => $this->organizationId]);
        return [
            'message' => $message,
            'project' => $this->activityTitle,
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
        return  'Publish Notification';
    }
}
