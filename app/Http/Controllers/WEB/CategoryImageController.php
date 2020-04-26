<?php

namespace App\Http\Controllers\WEB;

use App\Category;
use App\CategoryImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryImageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('category_image.admin.create', ['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'category_id'=>'exists:App\Category,id|required',
            'category_image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:3999',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('/category/image/create')->with('errors', $validator->errors()->getMessages());
        }
        if ($request->hasFile('category_image')) {
            $filename_with_ext = $request->file('category_image')->getClientOriginalExtension();
            $filename = pathinfo($filename_with_ext, PATHINFO_FILENAME);
            $extension = $request->file('category_image')->getClientOriginalExtension();
            $filename_to_store = $filename.'_'.time().'.'.$extension;
            $path = $request->file('category_image')->storeAs('public/category_images', $filename_to_store);
            $pathname = 'category_images/'.$filename_to_store;
        }
        $category_image = new CategoryImage();
        $category_image['category_id'] = $request['category_id'];
        $category_image['category_image_location'] = $pathname;
        $category_image->save();

        return redirect('/category/image/create')->with('success','category image added: '.$filename);
    }
}
