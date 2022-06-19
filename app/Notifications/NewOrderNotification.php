<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;

// use App\Models\Order;


class NewOrderNotification extends Notification
{
    use Queueable;
    private $order;
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($order)
    {
        // the order class
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database',
            FcmChannel::class
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase()
    {

        // dd($this->order);
        return [
            'title' => $this->order['title'],
            'body' => $this->order['body'],
            'order_id' => $this->order['order_id'],
        ];
    }
    // public function toMail($notifiable)
    // {

    //     return (new MailMessage)
    //         ->subject($this->order['subject'])
    //         ->line($this->order['body'])
    //         ->action($this->order['text'], $this->order['url'])
    //         ->line($this->order['thankyou']);
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
            'id'     => $this->order->id,
            'status' => 'placed',
        ];
    }
}
