<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use stdClass;

class DashboardController extends Controller
{

    public function index()
    {

        $firstChart = Product::skip(0)->take(5)->orderBy('count_orders', 'DESC')->get();
        foreach ($firstChart as $key => $value) {
            $finalDataFirstChart[] = [
                'label' => $value->name, 'y' => $value->count_orders
            ];
        }
        // -----------

        $secondChart = Product::skip(0)->take(10)->orderBy('count_orders', 'DESC')->get();
        foreach ($secondChart as $key => $value) {
            $finalDataSecondChart[] = [
                'y' => $value->count_orders, 'label' => $value->name
            ];
        }

        $customersCount = User::where('role_id', 4)->get()->count();
        $ordersCount = Order::get()->count();


        return view('voyager::dashboard.dashboard', compact(
            'finalDataFirstChart',
            'finalDataSecondChart',
            'customersCount',
            'ordersCount'
        ));
    }


    public function customerPage()
    {
        $customers = User::where('role_id', 4)->get();

        foreach ($customers as $key => $value) {


            $obj = new stdClass();
            $obj->id = $value->id;
            $obj->name = $value->name;
            $obj->created_at = $value->created_at;
            $obj->phone = $value->phone;
            $obj->orders_count = count($value->orders()->get());
            $customersData[] = $obj;
        }

       
        return view(
            'customers',
            compact('customersData')
        );
        // dd($customers);
    }
}
