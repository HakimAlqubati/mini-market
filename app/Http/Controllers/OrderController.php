<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderItem;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Unit;
use App\Models\UnitPrice;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use stdClass;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginateIndex(Request $request)
    {

        $order = Order::whereNotNull('id');

        if ($request->has('created_by')) {
            $order->where('created_by', '=', $request->created_by);
        }

        if ($request->has('id')) {
            $order->where('id', '=', $request->id);
        }

        if ($request->has('order_state')) {
            $order->where('order_state', '=', $request->order_state);
        }


        // $order->with('items');
        // $order->get();
        $final = [];
        if (count($order->get()) > 0) {
            foreach ($order->orderBy('id', 'DESC')->skip($request->page_id)->take(10)->get() as $key => $value) {
                $obj = new stdClass();
                $obj->id = $value->id;
                $obj->notes = $value->notes;
                $obj->customer_address = $value->customer_address;
                $obj->order_state = $value->order_state;
                $obj->total_price = $value->total_price;
                $obj->created_by = $value->created_by;
                $obj->customer_name = User::where('id',  $value->created_by)->first()->name;
                $obj->customer_phone = User::where('id',  $value->created_by)->first()->phone_no;
                $obj->shop_notes = $value->manager_notes;
                $obj->created_at = $value->created_at;
                $obj->updated_at = $value->updated_at;

                $obj->order_items = $this->getOrderItems($value->id);
                $final[] = $obj;
            }
        } else {
            $final = ['result' => 'there is no data'];
        }


        return $final;
    }
    public function index(Request $request)
    {

        $order = Order::whereNotNull('id');

        if ($request->has('created_by')) {
            $order->where('created_by', '=', $request->created_by);
        }

        if ($request->has('id')) {
            $order->where('id', '=', $request->id);
        }

        if ($request->has('order_state')) {
            $order->where('order_state', '=', $request->order_state);
        }


        // $order->with('items');
        // $order->get();
        $final = [];
        if (count($order->get()) > 0) {
            foreach ($order->orderBy('id', 'DESC')->get() as $key => $value) {
                $obj = new stdClass();
                $obj->id = $value->id;
                $obj->notes = $value->notes;
                $obj->customer_address = $value->customer_address;
                $obj->order_state = $value->order_state;
                $obj->total_price = $value->total_price;
                $obj->created_by = $value->created_by;
                $obj->customer_name = User::where('id',  $value->created_by)->first()->name;
                $obj->customer_phone = User::where('id',  $value->created_by)->first()->phone_no;
                $obj->shop_notes = $value->manager_notes;
                $obj->created_at = $value->created_at;
                $obj->updated_at = $value->updated_at;

                $obj->order_items = $this->getOrderItems($value->id);
                $final[] = $obj;
            }
        } else {
            $final = ['result' => 'there is no data'];
        }


        return $final;
    }


    public function getOrderItems($orderId)
    {
        $data = OrderItems::where('order_id', $orderId)->orderBy('id', 'DESC')->get();
        $final = [];
        foreach ($data as   $value) {
            $obj = new stdClass();
            $obj->id = $value->id;
            $obj->product_id = $value->product_id;
            $obj->product_name = Product::where('id', $value->product_id)->first()->name;
            $obj->product_code = Product::where('id', $value->product_id)->first()->code;
            $obj->english_name = Product::where('id', $value->product_id)->first()->english_name;
            $obj->product_image =
                (str_contains(Product::where('id', $value->product_id)->first()->image, ',') ? explode(',',  Product::where('id', $value->product_id)->first()->image)[0] :  Product::where('id', $value->product_id)->first()->image);
            $obj->unit_id = $value->unit_id;
            $obj->unit_name = Unit::where('id', $value->unit_id)->first()->name;
            $obj->english_unit_name = Unit::where('id', $value->unit_id)->first()->english_name;
            $obj->order_id = $value->order_id;
            $obj->qty = $value->qty;
            $obj->price = $value->price;
            $obj->color_id = $value->color_id;
            $final[] = $obj;
        }
        return $final;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $result =    DB::transaction(function () use ($request) {
            $newOrder = $this->addOrder(
                $request->notes,
                'ordered',
                $request->address,
                $request->newAddress,

                $request->user()->id

            );
            $all_products = $this->addOrderDetails($request->orderItems, $newOrder->id);


            $total_price = 0;
            foreach ($all_products as  $value) {
                $total_price += $value['price'];
            }

            OrderItems::insert($all_products);
            $this->addOrderTotalPrice($newOrder->id, $total_price);


            return   $newOrder->with('order_items')->where('id', $newOrder->id)->get();
            // return $newOrder->with('items')->where('id', $newOrder->id)->get();
        });
        return $result;
    }



    public function addOrder($notes, $orderState, $address, $isAddressNew, $createdBy)
    {

        if ($isAddressNew == '1') {

            $data = new Address([
                'user_id' => $createdBy,
                'address' => $address
            ]);
            $data->save();
        }

        $order = new Order(
            [
                'notes' =>  $notes,
                'order_state' => $orderState,
                'customer_address' => $address,
                'created_by' =>  $createdBy

            ]
        );
        $order->save();
        return $order;
    }


    public function addOrderDetails(array $orderDetails,  $orderId)
    {

        foreach ($orderDetails as   $data) {
            $unitPrice = $this->getUnitPriceData($data['product_id'], $data['unit_id']);
            $resultPrice = $unitPrice->price;
            $obj = new stdClass();
            $obj->product_id = $data['product_id'];
            $obj->unit_id =  $data['unit_id'];
            $obj->qty = $data['qty'];
            $obj->order_id = $orderId;
            $obj->price = $resultPrice  * $data['qty'];
            $obj->color_id = $data['color_id'];
            // to update orders quqntity in products
            $productData = Product::where('id', $data['product_id'])->first();

            $orders_count = $productData->count_orders;
            $productData->count_orders = $orders_count + $data['qty'];
            $productData->save();
            // -------


            $answers[] = $obj;
        }



        if (count($orderDetails) == 0) {
            $answers = array();
        }

        $answers =   json_decode(json_encode($answers), true);
        $all_products = array_merge($answers);
        return $all_products;
    }


    public function getUnitPriceData($productId, $unitId)
    {
        return UnitPrice::where(
            [
                [
                    'product_id', '=', $productId
                ],
                [
                    'unit_id', '=', $unitId
                ]
            ]
        )->first();
    }

    public function addOrderTotalPrice($orderId, $totalPrice)
    {
        $order = Order::find($orderId);
        $order->total_price = $totalPrice;
        return $order->save();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function sendNotification()
    {

        $SERVER_API_KEY = 'AAAA5_sMJeY:APA91bFk6Af-tHbSJWc-qEKBYQsVNxA8JE8g9onU3IbzzAXTQWNmxXH7MrKizlcgo5DdySg6M5nVOUx15k0MBrMle4LS-ZEZ3nlJic8yIXKz4gTndKCWiek5Y2VlbG_czqUU7P5uSHVI';

        $token_1 = 'cQnTccPYTuSLhxWTFlmpri:APA91bGuHyJpOYyOQK1owQ7CiO_lkzjAH3gWqiDnUHPDIw0rquqO5FRvDeQTzyp47gZvsriXHXMO4aqeuv7Ss7VYyW1VhucDH51emR1tiz-UhLTPzbQuFzJ__G4YG-Nxv5vOcUCtUkjs';

        $data = [

            "registration_ids" => [
                $token_1
            ],

            "notification" => [
                "title" => 'Welcome',
                "body" => 'Description',
                "sound" => "default" // required for sound on ios
            ],

        ];
        $dataString = json_encode($data);
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
        curl_exec($ch);
        // $response = curl_exec($ch);

        // dd($response);
    }

    public function updateState(Request $request)
    {

        $order = Order::find($request->id);
        // return $order;
        $order->order_state = $request->order_state;
        $order->manager_notes = $request->notes;
        $update =  $order->save();

        if ($update == 1) {
            return [
                'done'
            ];
        }
    }
}
