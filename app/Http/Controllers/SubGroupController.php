<?php

namespace App\Http\Controllers;

use App\Models\SubGroup;
use Illuminate\Http\Request;
use stdClass;

class SubGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function test()
    {
        // $subGroups = SubGroup::with('translations')->get();
        $subGroups = SubGroup::withTranslations()->get();;
        return $subGroups;
        // return $subGroups[8]->name;
    }
    public function index(Request $request)
    {
        $data = SubGroup::where('active', 1);

        if ($request->has('id')) {
            $data->where('id', '=', $request->id);
        }

        if ($request->has('active')) {
            $data->where('active', '=', $request->active);
        }

        if ($request->has('parent_id')) {
            $data->where('parent_id', '=', $request->parent_id);
        }


        // if ($request->withProducts == 1) {
        $data->with('products');
        // }


        $final = $data->get();

        // return $final;
        $finalResult = [];
        foreach ($final as $key => $value) {
            $obj = new stdClass();
            $obj->id = $value->id;
            $obj->name = $value->name;
            $obj->description = $value->description;
            $obj->image = $value->image;
            $obj->active = $value->active;
            $obj->parent_id = $value->parent_id;
            $obj->created_at = $value->created_at;
            $obj->updated_at = $value->updated_at;
            $obj->english_name = $value->english_name;
            $obj->products = $value->products;

            if (count($value->products) > 0) {
                $finalResult[] = $obj;
            }
        }
        return $finalResult;
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
     * @param  \App\Models\SubGroup  $subGroup
     * @return \Illuminate\Http\Response
     */
    public function show(SubGroup $subGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubGroup  $subGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(SubGroup $subGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubGroup  $subGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubGroup $subGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubGroup  $subGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubGroup $subGroup)
    {
        //
    }

    public function getSubGroupOfMain(Request $request)
    {
       

        if (!$request->parent_id) {
            $html = '<option value="">' . trans('global.pleaseSelect') . '</option>';
        } else {
            $html = '';
            $subGroups = SubGroup::where('parent_id', $request->parent_id)->get();
            foreach ($subGroups as $subGroup) {
                $html .= '<option value="' . $subGroup->id . '">' . $subGroup->name . '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
