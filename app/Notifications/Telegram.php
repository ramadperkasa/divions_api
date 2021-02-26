<?php

namespace App\Notifications;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Support\Str;
use NotificationChannels\Telegram\TelegramFile;
use Illuminate\Notifications\Notification;

class Telegram extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     //
    // }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function __construct($berita)
    {
        $this->berita = $berita;
    }

    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            // Optional recipient user id.
            ->to('@NewsPots')
            // Markdown supported.
            ->content(
                $this->berita['judul'] .
            '
            Temukan berita ini di http://demo.news.dipointer.com/id/page-news/' . Str::slug($this->berita['judul'], '-'));
        // (Optional) Inline Buttons
        // ->button('View Invoice', 'http://dipointer.com')
        // ->button('Download Invoice', 'http://dipointer.com');
    }

    // public function toTelegram($notifiable)
    // {
    //     return TelegramMessage::create()
    //         // Optional recipient user id.
    //         ->to('@ramadwiyantarachannel')
    //         // Markdown supported.
    //         ->content("Hello there!\nYour invoice has been *PAID*")
    //         // (Optional) Inline Buttons
    //         ->button('View Invoice', 'http://dipointer.com')
    //         ->button('Download Invoice', 'http://dipointer.com');
    // }

    // public function toTelegram($notifiable)
    // {
    //     // return TelegramFile::create()
    //     //     ->to('@ramadwiyantarachannel')
    //     //     ->content('Hello epribadih');

    //     return TelegramFile::create()
    //         ->to('@ramadwiyantarachannel') // Optional
    //         ->content('Awesome *bold* text and [inline URL](http://www.example.com/)');

    //     // OR using a helper method with or without a remote file.
    //     // ->photo('https://file-examples.com/wp-content/uploads/2017/10/file_example_JPG_1MB.jpg');
    // }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
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
