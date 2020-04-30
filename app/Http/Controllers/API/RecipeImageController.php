<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class RecipeImageController extends Controller
{
    //
    public function show($recipe_id) {
        $images = DB::table('recipe_images')
            ->select()
            ->where('id', $recipe_id)
            ->get();
        if (isset($images)) {
            foreach ($images as $image) {
                $image->recipe_image_location = asset('storage/'.$image->recipe_image_location);
            }
            return response($images, 200, Config::get('constants.jsonContentType'));
        }
        return response(['success'=>false, 'error_message'=>"recipe id: $recipe_id not found"], 404, Config::get('constants.jsonContentType'));
    }
}
