<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Carbon\Carbon;

class ImportUsersRequestProcessedNotification extends Notification
{
    use Queueable;
    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $importUsersRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userName, $importUsersRequest)
    {
        $this->userName = $userName;
        $this->importUsersRequest = $importUsersRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
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
            ->greeting('Hello ' . $this->userName . '!')
            ->line('Your import users request [' . $this->exportUsersRequest['export_request_id'] . '] has been completed.')

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
        $exportUsersRequestId = $this->exportUsersRequest['export_request_id'];
        $message = "Import Users Request [$exportUsersRequestId] has been imported successfully.";

        return [
            'message' => $message,
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
        return new BroadcastMessage(
            array(
                'notifiable_id' => $notifiable->id,
                'notifiable_type' => get_class($notifiable),
                'data' => $this->toDatabase($notifiable),
                'notifiable' => $notifiable,
                'read_at' => null,
                'created_at' => $timestamp->diffForHumans(),
                'updated_at' => $timestamp->diffForHumans(),
            )
        );
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