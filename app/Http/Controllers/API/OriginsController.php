<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Origin;
use Illuminate\Http\Request;

class OriginsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Origin::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $origin_id
     * @return \Illuminate\Http\Response
     */
    public function show($origin_id)
    {
        //
        return Origin::find($origin_id);
    }
}
