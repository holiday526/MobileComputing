<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Recipe;
use App\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RecipesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'scopes:admin'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Recipe::all();
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
            'name' => 'string|min:2|max:255|required|unique:recipes,name',
            'difficulty' => 'integer|min:1|max:5|required',
            'time_require' => 'numeric|min:0.1|required',
            'calories' => 'numeric|min:0.1|required',
            'method' => 'string|required',
            'ingredient' => 'array|required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if (isset($request->ingredient)) {
            foreach ($request->ingredient as $ingred) {
                $check = Food::find($ingred['food_id']);
                if (!isset($check)) {
                    $validator->errors()->add('ingredient', 'food id is not found');
                }
            }
        }
        if (count($validator->errors()) > 0){
            return response(
                ['success'=>false, 'error_message'=>$validator->errors()->getMessages()],
                400,
                Config::get('constants.jsonContentType')
            );
        }
        $recipe = new Recipe();
        $recipe->name = $request->name;
        $recipe->difficulty = $request->difficulty;
        $recipe->time_require = $request->time_require;
        $recipe->calories = $request->calories;
        $recipe->method = $request['method'];
        $recipe->ingredient = $request->ingredient;
        $recipe->save();
        return response(
            ['success'=>true, 'recipe_id'=>$recipe->id],
            201,
            Config::get('constants.jsonContentType')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($recipe_id)
    {
        //
        $recipes = Recipe::find($recipe_id);
        return isset($recipes) ? response($recipes, 200, Config::get('constants.jsonContentType')) : response(['success'=>false], 404, Config::get('constants.jsonContentType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $recipe_id)
    {
        $recipe = Recipe::find($recipe_id);
        if (!isset($recipe)) {
            return response(['success'=>false], 404, Config::get('constants.jsonContentType'));
        }
        $rules = [
            'name' => 'string|min:2|max:255',
            'difficulty' => 'integer|min:1|max:5',
            'time_require' => 'numeric|min:0.1',
            'calories' => 'numeric|min:0.1',
            'method' => 'string',
            'ingredient' => 'array'
        ];
        $validator = Validator::make($request->all(), $rules);
        if (isset($request->ingredient)) {
            foreach ($request->ingredient as $ingred) {
                $check = Food::find($ingred['food_id']);
                if (!isset($check)) {
                    $validator->errors()->add('ingredient', 'food id is not found');
                }
            }
        }
        if (count($validator->errors()) > 0) {
            return response(
                ['success'=>false, 'error_message'=>$validator->errors()->getMessages()],
                400,
                Config::get('constants.jsonContentType')
            );
        }
        $update_value = [];
        if (isset($request->name)) $update_value['name'] = $request->name;
        if (isset($request->difficulty)) $update_value['difficulty'] = $request->difficulty;
        if (isset($request->time_require)) $update_value['time_require'] = $request->time_require;
        if (isset($request->calories)) $update_value['calories'] = $request->calories;
        if (isset($request['method'])) $update_value['method'] = $request['method'];
        if (isset($request->ingredient)) $update_value['ingredient'] = $request->ingredient;
        DB::table('recipes')->where('id', $recipe_id)->update($update_value);
        $recipe = Recipe::find($recipe_id);
        return response(
            ['success'=>true, 'updated_recipe'=>$recipe],
            202,
            Config::get('constants.jsonContentType')
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($recipe_id)
    {
        //
        $recipe = Recipe::find($recipe_id);
        if (!isset($recipe)) {
            return response(['success'=>false, 'error_message'=>'recipe_id: '.$recipe_id.' not found'], 404, Config::get('constants.jsonContentType'));
        }
        DB::table('recipes')->where('id', $recipe_id)->delete();
        return response(['success'=>true], 204, Config::get('constants.jsonContentType'));
    }
}
