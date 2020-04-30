<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\RecipeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:admin']);
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
            'recipe_id' => 'exists:App\Recipe,id|required',
            'recipe_image' => 'image|max:3999|required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response(['success'=>false, 'error_message'=>$validator->errors()->getMessages()], 400);
        }

        $filename_with_ext = $request->file('recipe_image')->getClientOriginalExtension();
        $filename = pathinfo($filename_with_ext, PATHINFO_FILENAME);
        $extension = $request->file('recipe_image')->getClientOriginalExtension();
        $filename_to_store = $filename.'_'.time().'.'.$extension;
        $path = $request->file('recipe_image')->storeAs('/public/recipe_image', $filename_to_store);
        $pathname = 'recipe_image/'.$filename_to_store;

        $recipe_image = new RecipeImage();
        $recipe_image['recipe_id'] = $request['recipe_id'];
        $recipe_image['recipe_image_location'] = $pathname;
        if (isset($request['index_photo'])) {
            $recipe_image = $request['index_photo'];
        }
        $recipe_image->save();
        $recipe_image = RecipeImage::find($recipe_image->id);
        return response(['success'=>true, 'recipe_image'=>$recipe_image], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $rules = [
            'recipe_image_id' => 'exists:App\RecipeImage,id'
        ];

    }
}
