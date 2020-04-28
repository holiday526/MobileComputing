<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CategoryImageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        $categories = Category::select('id')->get()->toArray();
        $category_images = DB::table('categories')
            ->join('category_images', 'category_images.category_id', '=', 'categories.id')
//            ->whereIn('category_images.category_id',$categories)
            ->get();
        foreach ($category_images as $category_image) {
            $category_image->category_image_location = asset('storage/'.$category_image->category_image_location);
        }
        return response($category_images, 200, Config::get('constants.jsonContentType'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($category_id)
    {
        //
        $category_image = DB::table('categories')
            ->join('category_images', 'category_images.category_id', '=', 'categories.id')
            ->where('category_id', $category_id)
            ->first();
        if (isset($category_image)) {
            $category_image->category_image_location = asset('storage/'.$category_image->category_image_location);
            return response(array($category_image), 200, Config::get('constants.jsonContentType'));
        }
        return response(['success'=>false,'error_message'=>"category_id: $category_id not found"]);
    }
}
