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
Route::delete('/category/{category_id}', 'API\Admin\CategoriesController@destroy');
Route::apiResource('/category/image', 'API\CategoryImageController')->except(['store', 'destroy', 'update']);


// admin food image
Route::delete('/admin/food/image/{food_image_id}', 'API\Admin\FoodImageController@destroy');
Route::post('/admin/food/image', 'API\Admin\FoodImageController@store');

// user food image
Route::apiResource('/food/image', 'API\FoodImageController')->except(['store', 'destroy', 'update']);
Route::get('/food/image/category/{category_id}', 'API\FoodImageController@foodCategoryIndex');

// food origin
Route::apiResource('origin', 'API\OriginsController')->except(['destroy', 'update', 'store']);

// food
Route::get('/food/promotion', 'API\FoodsController@getPromotionItem');
Route::get('/food/hot_item', 'API\FoodsController@getHotItems');
Route::apiResource('/food', 'API\FoodsController')->except(['store', 'destroy', 'update']);

// normal ppl get all images of specific recipe
Route::get('/recipe/image/{recipe_id}', 'API\RecipeImageController@show');

// admin recipe
Route::apiResource('/recipe/image', 'API\Admin\RecipeImagesController')->except(['index', 'show', 'update']);

// user recipe
Route::apiResource('/recipe', 'API\RecipesController');



Route::group(['middleware'=>'auth:api'], function() {
    // admins' order
    Route::apiResource('/admin/order', 'API\Admin\OrdersController');

    // users' order
    Route::apiResource('/order', 'API\OrdersController');
});
