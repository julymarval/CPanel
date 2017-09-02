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
    return view('form');
});

/**
* Users Route
**/
Route::group(['prefix' => 'admin'],function(){
    
    Route::resource('users','UserController', [
        'only' => ['index', 'store', 'create', 'destroy', 'show','edit']]);
    Route::post('users/{id}', [
        'uses' => 'UserController@update',
        'as'   => 'users.update']);
});


/**
* Sales Route
**/
Route::group(['prefix' => 'api'],function(){
    
    Route::resource('sales','SalesController', [
        'only' => ['index', 'store', 'create', 'destroy', 'show','edit']]);
    Route::post('sales/{id}', [
        'uses' => 'SalesController@update',
        'as'   => 'sales.update']);
});


/**
* Shows Route
**/
Route::group(['prefix' => 'api'],function(){
    
    Route::resource('shows','ShowsController',[
        'only' => ['index', 'store', 'create', 'destroy', 'show','edit']]);
    Route::post('shows/{id}', [
        'uses' => 'ShowsController@update',
        'as'   => 'shows.update']);
});


/**
* Events Route
**/
Route::group(['prefix' => 'api'],function(){
    
    Route::resource('events','EventsController',[
        'only' => ['index', 'store', 'create', 'destroy', 'show','edit']]);
    Route::post('events/{id}', [
        'uses' => 'EventsController@update',
        'as'   => 'events.update']);
});


/**
* Sponsors Route
**/
Route::group(['prefix' => 'api'],function(){

    Route::resource('sponsors','SponsorsController',[
        'only' => ['index', 'store', 'create', 'destroy', 'show','edit']]);
    Route::post('sponsors/{id}', [
        'uses' => 'SponsorsController@update',
        'as'   => 'sponsors.update']);
});


/**
* Volunteers Route
**/
Route::group(['prefix' => 'api'],function(){
    
    Route::resource('volunteers','VolunteersController', [
        'only' => ['index', 'store', 'create', 'destroy', 'show','edit']]);
    Route::post('volunteers/{id}', [
        'uses' => 'VolunteersController@update',
        'as'   => 'volunteers.update']);
});


/**
* Auth Route for admin login
**/
Route::group(['prefix' => 'admin'],function(){
    
        Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
        Route::post('authenticate', 'AuthenticateController@authenticate');
    
});

/**
* Donate Route
**/
Route::group(['prefix' => 'api'],function(){
    
    Route::resource('donate','DonateController',[
        'only' => ['index', 'store', 'create', 'destroy', 'show']]);
});

/**
* UpdateDB Route
**/
Route::group(['prefix' => 'admin'],function(){
    
    Route::resource('updatedb','UpdateDBController',['only' => ['index']]);
});