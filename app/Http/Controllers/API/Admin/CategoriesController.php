<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('scopes:admin');
    }

    public function destroy($category_id) {
        DB::table('categories')->where('id', $category_id)->delete();
        return response(['success'=>true], 200, Config::get('constants.jsonContentType'));
    }
}
