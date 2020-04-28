<?php

namespace App\Http\Controllers\API;

use App\FoodImage;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class FoodImageController extends Controller
{
    private function joinCategoriesAndFoodImages() {
        return DB::table('foods')
            ->leftJoin('categories',  'categories.id', '=', 'foods.category_id')
            ->leftJoin('food_images', 'food_images.food_id', '=', 'foods.id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return the all information of all foods including food images
        $food_images = $this->joinCategoriesAndFoodImages()
            ->select('foods.*', 'food_images.*')
            ->where('index_photo', 1)
            ->get();
        if (isset($food_images)) {
            foreach($food_images as $food_image) {
                $food_image->food_image_location = asset('storage/'.$food_image->food_image_location);
            }
            return response($food_images, 200, Config::get('constants.jsonContentType'));
        }
        return abort(404);
    }

    // return all the food index photo in a specific category
    public function foodCategoryIndex($category_id) {
        $food_images = $this->joinCategoriesAndFoodImages()
            ->select('foods.*', 'food_images.*')
            ->where('index_photo', 1)
            ->where('foods.category_id', $category_id)
            ->get();
        if (isset($food_images)) {
            foreach($food_images as $food_image) {
                $food_image->food_image_location = asset('storage/'.$food_image->food_image_location);
            }
            return response($food_images, 200, Config::get('constants.jsonContentType'));
        }
        return abort(404);
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
        $images = DB::table('food_images')->select()->where('food_id', $food_id)->get();
        if (isset($images)) {
            foreach ($images as $image) {
                $image->food_image_location = asset('storage/'.$image->food_image_location);
            }
            return response($images, 200, Config::get('constants.jsonContentType'));
        }
        return abort(404);
    }
}
