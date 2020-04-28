<?php

namespace App\Http\Controllers\API;

use App\Food;
use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['scopes:user']);
    }

    /**
     * Display a listing of the resource.
     * Only return all user orders
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!(Auth::user())) {
            return response(['success'=>false], 400, Config::get('constants.jsonContentType'));
        }
        $user = Auth::user();
        $orders = DB::table('orders')->select('*')->where('user_id', $user->id)->get();
        return response($orders, 200, Config::get('constants.jsonContentType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()) {
            return response(['success'=>false], 403, Config::get('constants.jsonContentType'));
        }
        $rules = [
            'items' => 'array|required',
            'address' => 'string|required|min:5',
            'delivery_time' => 'date_format:Y-m-d\TH:i:sP|required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if (count($request->items) <= 0 ) {
            $validator->errors()->add('items', 'items cannot be empty');
        }
        foreach($request->items as $item) {
            if (!isset($item['food_id'])) {
                $validator->errors()->add('items', 'food_id is not set');
            }
            if (!isset($item['quantity'])) {
                $validator->errors()->add('items', 'quantity is not set');
            }
            $food = Food::find($item['food_id']);
            if (!isset($food)) {
                $validator->errors()->add('items', 'food_id is not found');
            }
            if ($item['quantity'] <= 0) {
                $validator->errors()->add('items', 'quantity cannot be smaller than or equal to 0');
            }
        }
        if (count($validator->errors()) > 0) {
            return response(['success'=>false, 'error_message'=>$validator->errors()->getMessages()], 400, Config::get('constants.jsonContentType'));
        }
        $order = new Order();
        $order->items = $request->items;
        $order->user_id = Auth::user()->id;
        $order->address = $request->address;
        $order->delivery_time = $request->delivery_time;
        $order->save();
        $order = Order::find($order->id);
        return response(['success'=>true, 'order_created'=>$order],201, Config::get('constants.jsonContentType'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($order_id)
    {
        if (!Auth::user()) {
            return response(['success'=>false], 403, Config::get('constants.jsonContentType'));
        }

        $order = Order::find($order_id);
        if (!isset($order)) {
            return response(['success'=>false, 'error_message'=>'order not found'], 404, Config::get('constants.jsonContentType'));
        }

        if ($order->user_id != Auth::user()->id) {
            return response(['success'=>false, 'not your order']);
        }

        return response($order, 200, Config::get('constants.jsonContentType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $order_id)
    {
        if (!Auth::user()) {
            return response(['success'=>false], 403, Config::get('constants.jsonContentType'));
        }
        $order = Order::find($order_id);
        if (!isset($order)) {
            return response(['success'=>false, 'error_message'=>'order not found'], 404, Config::get('constants.jsonContentType'));
        }
        if ($order->user_id != Auth::user()->id) {
            return response(['success'=>false, 'not your order'], 403, Config::get('constants.jsonContentType'));
        }
        $rules = [
            'items' => 'array',
            'address' => 'string|min:5',
            'delivery_time' => 'date_format:Y-m-d\TH:i:sP'
        ];
        $value_update = [];
        $validator = Validator::make($request->all(), $rules);
        if (count($validator->errors()) > 0) {
            return response(['success'=>false, 'error_message'=>$validator->errors()->getMessages()], 400, Config::get('constants.jsonContentType'));
        }
        if (isset($request->items)) $value_update['items'] = $request->items;
        if (isset($request->address)) $value_update['address'] = $request->address;
        if (isset($request->address)) $value_update['delivery_time'] = $request->delivery_time;
        DB::table('orders')->where('id', $order_id)->update($value_update);
        $order = Order::find($order_id);
        return response(['success'=>true, 'updated_order'=>$order], 202, Config::get('constants.jsonContentType'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $order_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($order_id)
    {
        if (!Auth::user()) {
            return response(['success'=>false], 403, Config::get('constants.jsonContentType'));
        }
        $order = Order::find($order_id);
        if (!isset($order)) {
            return response(['success'=>false, 'error_message'=>'order not found'], 404, Config::get('constants.jsonContentType'));
        }
        if ($order->user_id != Auth::user()->id) {
            return response(['success'=>false, 'not your order'], 403, Config::get('constants.jsonContentType'));
        }
        DB::table('orders')->where('id', $order_id)->where('user_id', Auth::user()->id)->delete();
        return response(['success'=>true], 204, Config::get('constants.jsonContentType'));
    }
}
