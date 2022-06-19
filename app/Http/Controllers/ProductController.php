<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Unit;
use App\Models\UnitPrice;
use Illuminate\Http\Request;
use stdClass;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     
    public function index(Request $request)
    {
        $data = Product::where('active', 1);

        if ($request->has('id')) {
            $data->where('id', '=', $request->id);
        }


        if ($request->has('active')) {
            $data->where('active', '=', $request->active);
        }


        if ($request->has('name')) {
            $data->where('name', 'like', '%' . $request->name . '%');
        }


        if ($request->has('group_id')) {
            $data->where('group_id', '=', $request->group_id);
        }

        $data = $data->get();
        // return $data;
        $final = [];
        foreach ($data as   $value) {
            $obj = new stdClass();
            $obj->id = $value->id;
            $obj->name = $value->name;
            $obj->code = $value->code;
            $obj->english_name = $value->english_name;
            $obj->description = $value->description;
            $obj->english_description = $value->english_description;
            $obj->image = $value->image;
            $obj->active = $value->active;
            $obj->group_id = $value->group_id;

            $obj->created_at = $value->created_at;
            // $obj->count = count($this->getUnitProdict($value->id));
            $obj->unit_prices = $this->getUnitProdict($value->id);
            $final[] = $obj;
        }
        // if ($request->withUnitPrices == 1) {
        //     $data->with('unitPrices');
        // }

        return $final;
    }


    public function getUnitProdict($productId)
    {
        $data = UnitPrice::where('product_id', $productId)->get();
        if (count($data) > 0) {
            foreach ($data as   $value) {
                $obj = new stdClass();
                $obj->id = $value->id;
                $obj->product_id = $value->product_id;
                $obj->product_name = Product::where('id', $value->product_id)->first()->name;

                $obj->unit_id = $value->unit_id;
                $obj->unit_name = Unit::where('id', $value->unit_id)->first()->name;
                $obj->unit_english_name = Unit::where('id', $value->unit_id)->first()->english_name;
                $obj->price = $value->price;
                $obj->colors = $value->colors;
                $final[] = $obj;
            }
        } else {
            $final = [];
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
