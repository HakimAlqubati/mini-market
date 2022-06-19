<?php

namespace App\Http\Controllers;

use App\Models\MainGroup;
use App\Models\SubGroup;
use Illuminate\Http\Request;
use stdClass;

class MainGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data = MainGroup::where('active', 1);

        if ($request->has('id')) {
            $data->where('id', '=', $request->id);
        }

        if ($request->has('active')) {
            $data->where('active', '=', $request->active);
        }


        // if ($request->withSubCategories == 1) {

        $data->with('subGroups');
        // }


        // foreach ($result as $key => $value) {
        //    $obj
        // }
        $data = $data->get();


        foreach ($data as $key => $value) {


            if (SubGroup::where('parent_id', $value->id)->get()->count() > 0) {
                $obj = new stdClass();
                $obj->id = $value->id;
                $obj->name = $value->name;
                $obj->description = $value->description;
                $obj->image = $value->image;
                $obj->active = $value->active;
                $obj->created_at = $value->created_at;
                $obj->updated_at = $value->updated_at;
                $obj->english_name = $value->english_name;
                $obj->english_description = $value->english_description;
                $obj->sub_groups = SubGroup::where('parent_id', $value->id)->get();
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MainGroup  $mainGroup
     * @return \Illuminate\Http\Response
     */
    public function show(MainGroup $mainGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MainGroup  $mainGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(MainGroup $mainGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MainGroup  $mainGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MainGroup $mainGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainGroup  $mainGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(MainGroup $mainGroup)
    {
        //
    }
}
