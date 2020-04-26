<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// user login
Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('details/admin', 'API\AdminController@details');
    Route::post('details', 'API\UserController@details');
});

// admin login
Route::post('/login/admin', 'API\AdminController@login');

// category image
Route::apiResource('/category/image', 'API\CategoryImageController')->except(['store', 'destroy', 'update']);

// food image
Route::apiResource('/food/image', 'API\FoodImageController')->except(['store', 'destroy', 'update']);
Route::get('/food/image/category/{category_id}', 'API\FoodImageController@foodCategoryIndex');

// food
Route::apiResource('/food', 'API\FoodsController')->except(['store', 'destroy', 'update']);
