<?php

namespace App\Http\Controllers\API\Admin;

use App\FoodImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

class FoodImageController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:admin']);
    }

    public function store(Request $request) {
        $rules = [
            'food_id'=>'exists:App\Food,id|required',
            'food_image'=>'image|max:3999|required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response(['success'=>false, 'error_message'=>$validator->errors()->getMessages()], 400);
        }

        $filename_with_ext = $request->file('food_image')->getClientOriginalExtension();
        $filename = pathinfo($filename_with_ext, PATHINFO_FILENAME);
        $extension = $request->file('food_image')->getClientOriginalExtension();
        $filename_to_store = $filename.'_'.time().'.'.$extension;
        $path = $request->file('food_image')->storeAs('/public/food_images', $filename_to_store);
        $pathname = 'food_images/'.$filename_to_store;

        $food_image = new FoodImage();
        $food_image['food_id'] = $request['food_id'];
        $food_image['food_image_location'] = $pathname;
        if (isset($request['index_photo'])) {
            $food_image['index_photo'] = true;
        }
        $food_image->save();
        $food_image = FoodImage::find($food_image->id);
        return response(['success'=>true, 'food_image'=>$food_image], 201);
    }

    public function destroy($food_image_id) {
        $rules = [
            'food_image_id' => 'exists:App\FoodImage,id'
        ];
        $validator = Validator::make(
            ['food_image_id'=>$food_image_id],
            $rules
        );
        if ($validator->fails()) {
            return response(['success'=>false, 'error_message'=>$validator->errors()->getMessages()], 404, Config::get('constants.jsonContentType'));
        }
        DB::table('food_images')->where('id', $food_image_id)->delete();
        return response(['success'=>true, 'message'=>'food image id: '.$food_image_id.' is deleted'], 202);
    }
}
