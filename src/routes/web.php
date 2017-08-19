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
    return view('welcome');
});

/**
* Users Route
**/
Route::group(['prefix' => 'admin'],function(){
    
    Route::resource('users','UserController');
});


/**
* Sales Route
**/
Route::group(['prefix' => 'api'],function(){
    
    Route::resource('sales','SalesController');
});


/**
* Shows Route
**/
Route::group(['prefix' => 'api'],function(){
    
    Route::resource('shows','ShowsController');
});


/**
* Events Route
**/
Route::group(['prefix' => 'api'],function(){
    
    Route::resource('events','EventsController');
});


/**
* Sponsors Route
**/
Route::group(['prefix' => 'api'],function(){

    Route::resource('sponsors','SponsorsController');
});


/**
* Volunteers Route
**/
Route::group(['prefix' => 'api'],function(){
    
    Route::resource('volunteers','VolunteersController');
});


/**
* Auth Route for admin login
**/
Route::group(['prefix' => 'admin'],function(){
    
        Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
        Route::post('authenticate', 'AuthenticateController@authenticate');
    
});