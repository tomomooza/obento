<?php

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
    if (Auth::check()) {
        return view('main');
    } else {
        return view('welcome');
    }
});

Auth::routes();

Route::middleware('auth')->group(function() {
    Route::get('/main', 'MainController@index')->name('main');
    Route::get('/ingredient', 'IngredientController@index')->name('ingredient');
    Route::post('/ingredient', 'IngredientController@post');
    Route::put('/ingredient', 'IngredientController@put');
    Route::get('/season', 'SeasonController@index')->name('season');
    Route::post('/season', 'SeasonController@post');
    Route::get('/dish', 'DishController@index')->name('dish');
    Route::post('/dish', 'DishController@post');
    Route::get('/ajax/dish', 'Ajax\AjaxDishController@get');
    Route::post('/ajax/dish', 'Ajax\AjaxDishController@post');
});