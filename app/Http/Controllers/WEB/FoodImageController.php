<?php

namespace App\Http\Controllers\WEB;

use App\Food;
use App\FoodImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FoodImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $food = Food::all();
        return view('food_image.admin.create', ['foods'=>$food]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'food_id'=>'exists:App\Food,id|required',
            'food_image'=>'image|max:3999|required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/food/image/create')->with('errors', $validator->errors()->getMessages());
        }
        if ($request->hasFile('food_image')) {
            $filename_with_ext = $request->file('food_image')->getClientOriginalExtension();
            $filename = pathinfo($filename_with_ext, PATHINFO_FILENAME);
            $extension = $request->file('food_image')->getClientOriginalExtension();
            $filename_to_store = $filename.'_'.time().'.'.$extension;
            $path = $request->file('food_image')->storeAs('/public/food_images', $filename_to_store);
            $pathname = 'food_images/'.$filename_to_store;
        }

        $food_image = new FoodImage();
        $food_image['food_id'] = $request['food_id'];
        $food_image['food_image_location'] = $pathname;
        if (isset($request['index_photo'])) {
            $food_image['index_photo'] = true;
        }
        $food_image->save();
        return redirect('/food/image/create')->with('success', 'food image added: '.$filename);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $food_id
     * @return \Illuminate\Http\Response
     */
    public function show($food_id)
    {
        //
        $image = DB::table('food_images')->select()->where('food_id', $food_id)->where('index_photo', '=', 1)->first();
        return view('food_image.admin.show', ['image'=>$image]);
    }
}
