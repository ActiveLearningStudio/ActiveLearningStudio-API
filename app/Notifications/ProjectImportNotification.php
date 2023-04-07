<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Carbon\Carbon;

class ProjectImportNotification extends Notification
{
    use Queueable;
    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $projectName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userName, $projectName)
    {
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
            ->line('Your import of Project [' . $this->projectName['name'] . '] has been completed. Please log back into <a href="' . url('/') . '">CurrikiStudio</a> and navigate to My Projects to access your shiny new project!')

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
        $projectTitle = $this->projectName['name'];
        $message = "Project [$projectTitle] has been imported successfully.";

        if ($this->projectName['isMetaDataImported'] === 404)
            $message = "The project [$projectTitle] was imported with warnings. Subject, Education Level, or Author Tags did not exist in the target environment. Please review your imported project.";

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