<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {




        // $user = User::find(1);
        $users = User::whereIn('role_id', [3, 1, 5])->get();
        // dd($users);

        // dd($users);

        $orderNotification = [
            'title' => 'new order no ' . $order->id,
            'body' => 'order from customer ' .  \App\Models\User::find($order->created_by)->name,
            'order_id' =>   $order->id,
        ];

        Notification::send($users, new NewOrderNotification($orderNotification));

        // dd($notification);

        // $user = User::first();


        // $orderNotification = [
        //     'subject' => 'new order',
        //     'body' => 'hi , this is new order',
        //     'text' => 'ddddddddd',
        //     'url' => url('/'),
        //     'thankyou' => 'thank you for using our app',
        // ];


        // $user->notify(new NewOrderNotification($orderNotification));
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        switch ($order->order_state) {
            case 'ordered':
                $title = 'Ordered';
                break;
            case 'processing':
                $title = 'Processing';
                break;
            case 'completed':
                $title = 'Completed';
                break;
            case 'cancelled':
                $title = 'Cancelled';
                break;
        }

        $customerName = $order->customer->name;
        $device_token = $order->customer->device_token;
        $SERVER_API_KEY = 'AAAA5_sMJeY:APA91bFk6Af-tHbSJWc-qEKBYQsVNxA8JE8g9onU3IbzzAXTQWNmxXH7MrKizlcgo5DdySg6M5nVOUx15k0MBrMle4LS-ZEZ3nlJic8yIXKz4gTndKCWiek5Y2VlbG_czqUU7P5uSHVI';
        $token_1 = $device_token;
        $data = [
            "registration_ids" => [
                $token_1
            ],
            "notification" => [
                "title" => 'Hi ' .  $customerName,
                "body" => 'Your order no ' . $order->id . ' is ' . $title . ' now',
                "sound" => "default" // required for sound on ios
            ],

        ];
        $dataString = json_encode($data);
        // $dataString =  $data;
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response =  curl_exec($ch);
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
