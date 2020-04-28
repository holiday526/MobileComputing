<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Order;
use League\OAuth2\Server\RequestEvent;

class FoodsController extends Controller
{
    private function joinCategory() {
        return DB::table('foods')
            ->leftJoin('categories', 'foods.category_id', '=', 'categories.id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // show all food
        $foods = $this->joinCategory()->select(['foods.*', 'categories.name'])->get();
        return response($foods, 200, Config::get('constants.jsonContentType'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($food_id)
    {
        //
        $food = $this->joinCategory()->select(['foods.*', 'categories.name'])
            ->where('foods.id', $food_id)
            ->get();
        if (isset($food)) {
            return response($food, 200, Config::get('constants.jsonContentType'));
        }
        return abort(404);
    }

    // TODO: return the items which are bought recently
    public function getHotItems() {
        $latest_orders = Order::orderBy('id', 'desc')->take(10)->get();
        if (!isset($latest_orders)) {
            return response(['success'=>false, 'error_message'=>'no hot item found'], 404, Config::get('constants.jsonContentType'));
        }
        // key = food id, value count
        $hot_items = [];
        foreach($latest_orders as $latest_order_items) {
            foreach($latest_order_items->items as $items) {
                if (!array_key_exists($items["food_id"], $hot_items)) {
                    $hot_items[$items["food_id"]] = 0;
                }
                $hot_items[$items['food_id']] += $items['quantity'];
            }
        }
        arsort($hot_items);
        $result_hot_items = [];
        foreach($hot_items as $k => $v) {
            array_push($result_hot_items, $this->joinCategory()->select('foods.*', 'categories.name as category_name')->where('foods.id', $k)->first());
        }
        return response($result_hot_items, 200, Config::get('constants.jsonContentType'));
    }

    public function getPromotionItem(Request $request) {
        $foods = $this->joinCategory()->select(['foods.*', 'categories.name'])
            ->having('foods.promotion', '>', 0);

        if (isset($request->sortBy)) {
            $sortBy = explode(" ", $request->sortBy);
            foreach ($sortBy as $item) {
                if ($item == 'discount') {
                    $foods->orderBy('foods.promotion', 'desc');
                }
                if ($item == 'category') {
                    $foods->orderBy('foods.category_id');
                }
            }
        } else {
            $foods->orderBy('foods.promotion', 'desc');
        }

        $foods = $foods->get();

        if (isset($foods)) {
            return response($foods, 200, Config::get('constants.jsonContentType'));
        }
        return response(['food'=>'not found'], 404, Config::get('constants.jsonContentType'));
    }
}
