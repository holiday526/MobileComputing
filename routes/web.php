<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm');
Route::get('/register/admin', 'Auth\RegisterController@showAdminRegisterForm');

Route::post('/login/admin', 'Auth\LoginController@adminLogin');
Route::post('/register/admin', 'Auth\RegisterController@createAdmin');

Route::view('/home', 'home')->middleware('auth');
Route::view('/admin', 'admin');

Route::resource('food/image', 'WEB\FoodImageController')->except(['index', 'edit', 'update', 'destroy']);
Route::post('food/update/{food_id}', 'WEB\FoodsController@update');
Route::resource('food', 'WEB\FoodsController')->except(['show', 'edit', 'update', 'destroy']);
Route::resource('category/image', 'WEB\CategoryImageController')
    ->names(
        [
            'index'=>'category.image.index',
            'create'=>'category.image.create',
            'store'=>'category.image.store',
            'show'=>'category.image.show',
            'edit'=>'category.image.edit',
            'update'=>'category.image.update',
            'destroy'=>'category.image.destroy',
        ]
    )
    ->except(['index', 'edit', 'update', 'destroy']);
Route::resource('category', 'WEB\CategoriesController')->except(['index', 'show', 'edit', 'update', 'destroy']);
