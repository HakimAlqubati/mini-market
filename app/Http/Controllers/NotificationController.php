<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use stdClass;

class NotificationController extends Controller
{
    public function send(Request $request)
    {


        $customer = User::findMany($request->user_id);

        $tokens = [];
        foreach ($customer as $key => $value) {

            $tokens[] = $value->device_token;
        }


        // return $tokens;




        // $device_token = $customer->device_token;


        // return $tks;
        $SERVER_API_KEY = 'AAAA5_sMJeY:APA91bFk6Af-tHbSJWc-qEKBYQsVNxA8JE8g9onU3IbzzAXTQWNmxXH7MrKizlcgo5DdySg6M5nVOUx15k0MBrMle4LS-ZEZ3nlJic8yIXKz4gTndKCWiek5Y2VlbG_czqUU7P5uSHVI';
        // $token_1 = $device_token;
        $data = [
            "registration_ids" =>
            $tokens,
            "notification" => [
                "title" =>  $request->title,
                "body" => $request->body,
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
        return $response;
    }
    public function webSend(Request $request)
    {




        // $customer = User::findMany($request->user_id);
        $customer = User::findMany($request->users);

        $tokens = [];
        foreach ($customer as $key => $value) {

            $tokens[] = $value->device_token;
        }





        // $device_token = $customer->device_token;


        // return $tks;
        $SERVER_API_KEY = 'AAAA5_sMJeY:APA91bFk6Af-tHbSJWc-qEKBYQsVNxA8JE8g9onU3IbzzAXTQWNmxXH7MrKizlcgo5DdySg6M5nVOUx15k0MBrMle4LS-ZEZ3nlJic8yIXKz4gTndKCWiek5Y2VlbG_czqUU7P5uSHVI';
        // $token_1 = $device_token;
        $data = [
            "registration_ids" =>
            $tokens,
            "notification" => [
                "title" =>  $request->title,
                "body" => $request->body,
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
        $response =  json_decode(curl_exec($ch))->success;

        if($response == 1){
            return redirect()->back()->with([
                'message'    =>   "تم",
                'alert-type' => 'success',
            ]);
        }else{
            return redirect()->back()->with([
                'message'    =>   "خطأ",
                'alert-type' => 'error',
            ]); 
        }

        return $response;
    }

    public function create()
    {

        return view('notifications.create');
    }
    public function topicCreate()
    {
        return view('notifications.topic-create');
    }

    public function topicSend(Request $request)
    {
        $obj = new stdClass();
        $obj->to =  "/topics/news";
        $obj->notification = [
            $obj->title => $request->title,
            $obj->body => $request->body
        ];

        return $obj;
    }
}
