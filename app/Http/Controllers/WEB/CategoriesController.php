<?php

namespace App\Http\Controllers\WEB;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Category::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.admin.create');
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
        $validator = Validator::make($request->all(), [
            'name' => 'string|min:2|unique:App\Category,name',
        ]);
        if ($validator->fails()) {
            return redirect('/category/create')->with('errors', $validator->errors()->getMessages());
        }

        $category = new Category;
        $category['name'] = $request['name'];
        $category->save();

        return redirect('/category/create')->with('success', 'category created: '.$category['name']);
    }
}
