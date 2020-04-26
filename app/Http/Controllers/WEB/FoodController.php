<?php

namespace App\Http\Controllers\WEB;

use App\Food;
use App\Http\Controllers\Controller;
use App\Origin;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
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
        $categories = Category::all();
        $origins = Origin::all();
        return view('food.admin.create', [
            'categories'=>$categories,
            'origins'=>$origins,
        ]);
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
        $rules = [
            'category_id'=>'exists:App\Category,id|required',
            'name' => 'string|min:2|required',
            'price' => 'numeric|gte:0.1|required',
            'weight' => 'numeric|gte:0|required',
        ];
        if (empty($request->origin_name) && empty($request->origin_id)) {
            $rules['origin_id'] = 'exists:App\Origin,id|required';
        }
        if (isset($request->origin_name)) {
            $rules['origin_name'] = 'string|min:2|unique:App\Origin,name|required';
        } else if (isset($request->origin_id)) {
            $rules['origin_id'] = 'exists:App\Origin,id|required';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/food/create')->with('errors', $validator->errors()->getMessages());
        }

        $food = new Food();
        $food->category_id = $request->category_id;
        $food->name = $request->name;
        $food->price = $request->price;
        $food->weight = $request->weight;
        if (isset($request->origin_id)) {
            $food->origin_id = $request->origin_id;
        } else if (isset($request->origin_name)) {
            $origin = new Origin();
            $origin->name = $request->origin_name;
            $origin->save();
            $food->origin_id = $origin->id;
        }
        $food->save();
        return redirect('/food/create')->with('success', 'food created');
    }
}
