<?php

namespace App\Http\Controllers\API\Admin;

use App\Food;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:admin']);
    }
    //
    public function index() {
        return Order::all();
    }

    public function show($order_id) {
        $order = Order::find($order_id);
        return isset($order) ? response($order, 200, Config::get('constants.jsonContentType')) : response(['success'=>false, 'error_message'=>'order id: '.$order_id.' not found'], 404, Config::get('constants.jsonContentType'));
    }

    public function store(Request $request) {
        $rules = [
            'items' => 'array|required',
            'user_id' => 'exists:App\User,id|required',
            'address' => 'string|min:5',
            'delivery_time' => 'date_format:Y-m-d\TH:i:sP|required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if (count($request->items) <= 0) {
            $validator->errors()->add('items', 'items cannot be empty');
        }
        foreach ($request->items as $item) {
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
        if(count($validator->errors())) {
            return response(['success'=>false, 'error_message'=>$validator->errors()->getMessages()], 400, Config::get('constants.jsonContentType'));
        }
        $order = new Order();
        $order->items = $request->items;
        $order->user_id = $request->user_id;
        $order->address = $request->address;
        $order->delivery_time = $request->delivery_time;
        $order->save();
        $order = Order::find($order->id);
        return response(['success'=>true, 'order_created'=>$order],201, Config::get('constants.jsonContentType'));
    }

    public function update($order_id, Request $request) {
        $order = Order::find($order_id);
        if (!isset($order)) {
            return response(['success'=>false, 'error_message'=>'order id: '.$order_id.' not found'], 404, Config::get('constants.jsonContentType'));
        }
        $rules = [
            'items' => 'array',
            'user_id' => 'exists:App\User,id',
            'address' => 'string',
            'delivery_time' => 'date_format:Y-m-d\TH:i:sP'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($order->user_id != $request->user_id){
            return response(['success'=>false, 'error_message'=>'user id: '.$request->user_id.' is not correct'], 404, Config::get('constants.jsonContentType'));
        }
        if (count($request->items) <= 0) {
            $validator->errors()->add('items', 'items cannot be empty');
        }
        foreach ($request->items as $item) {
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
        if(count($validator->errors())) {
            return response(['success'=>false, 'error_message'=>$validator->errors()->getMessages()], 400, Config::get('constants.jsonContentType'));
        }
        $value_update = [];
        if (isset($request->items)) $value_update['items'] = $request->items;
        if (isset($request->address)) $value_update['address'] = $request->address;
        if (isset($request->address)) $value_update['delivery_time'] = $request->delivery_time;
        DB::table('orders')->where('id', $order_id)->where('user_id', $request->user_id)->update($value_update);
        $order = Order::find($order_id);
        return response(['success'=>true, 'updated_order'=>$order], 202, Config::get('constants.jsonContentType'));
    }

    public function destroy($order_id) {
        $order = Order::find($order_id);
        if (!isset($order)) {
            return response(['success'=>false, 'error_message'=>'Order id: '.$order_id.' not found'], 404, Config::get('constants.jsonContentType'));
        }
        DB::table('orders')->where('id', $order_id)->delete();
        return response(['success'=>true], 204, Config::get('constants.jsonContentType'));
    }
}
