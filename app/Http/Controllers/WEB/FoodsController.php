<?php

namespace App\Http\Controllers\WEB;

use App\Food;
use App\Http\Controllers\Controller;
use App\Origin;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Validator;

class FoodsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index() {
        $foods = Food::all();
//        return $foods;
        return view('food.admin.index', ['foods'=>$foods]);
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
            'weight' => 'numeric|gte:0.1|required',
            'promotion' => 'numeric|gte:0.1|required',
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
        $food->promotion = $request->promotion;
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

    public function update($food_id, Request $request) {
//        dd($request->all());
        $rules = [
            'category_id' => 'exists:App\Category,id',
            'name' => 'string:2|required',
            'origin_id' => 'exists:App\Origin,id|required',
            'promotion' => 'numeric|min:0|max:100|required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return redirect('/food')->with('errors', $validator->errors()->getMessages());
        }
        $food = Food::find($food_id);
        if (isset($request->category_id)) {
            $food->category_id = $request->category_id;
        }
        $food->name = $request->name;
        $food->origin_id = $request->origin_id;
        $food->promotion = $request->promotion;
        $food->save();
        return redirect('/food')->with('success', "Food id: ".$food_id." Food name: ".$request->name." has been updated");
    }
}
