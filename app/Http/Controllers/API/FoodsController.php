<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

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

    // TODO: return the items which are brough recently
    public function getHotItem() {

    }

    public function getPromotionItem() {
        $foods = $this->joinCategory()->select(['foods.*', 'categories.name'])
            ->having('foods.promotion', '>', 0)
            ->get();
        if (isset($food)) {
            return response($food, 200, Config::get('constants.jsonContentType'));
        }
        return response(['food'=>'not found'], 404, Config::get('constants.jsonContentType'));
    }
}
