<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\FacebookPoster\FacebookPosterChannel;
use NotificationChannels\FacebookPoster\FacebookPosterPost;

class FacebookPoster extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     //
    // }
    // public function via($notifiable)
    // {
    //     return [FacebookPosterChannel::class];
    // }
    public function via($notifiable)
    {
        return [FacebookPosterChannel::class];
    }

    /** 
     * Get the Facebook post representation of the notification.
     *
     * @param  mixed  $notifiable.
     * @return \NotificationChannels\FacebookPoster\FacebookPosterPost
     */
    public function toFacebookPoster($notifiable) {
        return new FacebookPosterPost('Laravel notifications are awesome!');
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

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
