<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Twitter\TwitterStatusUpdate;

class Twitter extends Notification
{
    use Queueable;
    private $berita;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($berita)
    {
        $this->berita = $berita;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TwitterChannel::class];
    }

    public function toTwitter($post)
    {

        $ox = array(
            "webm",
            "mpg",
            "mp2",
            "mpeg",
            "mpe",
            "mpv",
            "mp4",
            "m4p",
            "m4v",
            "avi",
            "wmv",
            "mov",
            "qt",
            "flv",
            "swf",
            "avchd",
        );

        if (!is_file($this->berita['cover_image'])) {
            $type = 2;
        } else {
            if (in_array(strtolower(pathinfo($this->berita['cover_image'], PATHINFO_EXTENSION)), $ox)) {
                $type = 1;
            } else {
                $type = 0;
            }
        }
        // if ($type == 0) {
        //     return (new TwitterStatusUpdate($this->berita['judul'] .
        //         'Temukan berita ini di http://demo.news.dipointer.com/id/page-news/' . Str::slug($this->berita['judul'], '-')))
        //         ->withImage($this->berita['cover_image']);
        // } else {
        return (new TwitterStatusUpdate($this->berita['judul'] .
            'Temukan berita ini di http://demo.news.dipointer.com/id/page-news/' . Str::slug($this->berita['judul'], '-')));
        // }
    }

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
