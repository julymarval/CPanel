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

Route::get('/about', [
    'uses' => 'AboutController@index',
    'as'   => 'about']);

/**
* Admin Dashboard
**/
Route::get('/admin/dashboard', [
        'uses' => 'DashboardController@index',
        'as'   => 'dashboard']);

/**
* Home Route
**/
Route::get('/', [
        'uses' => 'HomeController@index',
        'as'   => 'home']);

/**
* Admin Route: shows
**/
Route::get('/admin/shows', [
    'uses' => 'AdminController@indexShows',
    'as'   => 'admin.shows']);


/**
* Admin Route: events
**/
Route::get('/admin/events', [
    'uses' => 'AdminController@indexEvents',
    'as'   => 'admin.events']);

/**
* Admin Route: sales
**/
Route::get('/admin/sales', [
    'uses' => 'AdminController@indexSales',
    'as'   => 'admin.sales']);


/**
* Admin Route: volunteers
**/
Route::get('/admin/volunteers', [
    'uses' => 'AdminController@indexVolunteers',
    'as'   => 'admin.volunteers']);

/**
* Admin Route: sponsors
**/
Route::get('/admin/sponsors', [
    'uses' => 'AdminController@indexSponsors',
    'as'   => 'admin.sponsors']);


/**
* Users Route
**/
Route::group(['prefix' => '/'],function(){
    
    Route::resource('users','UserController', [
        'only' => ['index', 'store', 'create', 'show','edit']]);
    Route::post('users/{id}', [
        'uses' => 'UserController@update',
        'as'   => 'users.update']);
    Route::get('users/{id}/destroy', [
        'uses' => 'UserController@destroy',
        'as'   => 'users.destroy']);
});


/**
* Sales Route
**/
Route::group(['prefix' => '/'],function(){
    
    Route::resource('sales','SalesController', [
        'only' => ['index', 'store', 'create', 'show','edit']]);
    Route::post('sales/{id}', [
        'uses' => 'SalesController@update',
        'as'   => 'sales.update']);
    Route::get('sales/{id}/destroy', [
        'uses' => 'SalesController@destroy',
        'as'   => 'sales.destroy']);
});


/**
* Shows Route
**/
Route::group(['prefix' => '/'],function(){
    
    Route::resource('shows','ShowsController',[
        'only' => ['index', 'store', 'create', 'destroy', 'show','edit']]);
    Route::post('shows/{id}', [
        'uses' => 'ShowsController@update',
        'as'   => 'shows.update']);
    Route::get('shows/{id}/destroy', [
        'uses' => 'ShowsController@destroy',
        'as'   => 'shows.destroy']);
});


/**
* Events Route
**/
Route::group(['prefix' => '/'],function(){
    
    Route::resource('events','EventsController',[
        'only' => ['index', 'store', 'create', 'destroy', 'show','edit']]);
    Route::post('events/{id}', [
        'uses' => 'EventsController@update',
        'as'   => 'events.update']);
    Route::get('events/{id}/destroy', [
        'uses' => 'EventsController@destroy',
        'as'   => 'events.destroy']);
    Route::get('events/{id}',[
        'uses' => 'EventsController@show',
        'as'   => 'events.show']); 
});


/**
* Sponsors Route
**/
Route::group(['prefix' => '/'],function(){

    Route::resource('sponsors','SponsorsController',[
        'only' => ['index', 'store', 'create', 'destroy', 'show','edit']]);
    Route::post('sponsors/{id}', [
        'uses' => 'SponsorsController@update',
        'as'   => 'sponsors.update']);
    Route::get('sponsors/{id}/destroy', [
        'uses' => 'SponsorsController@destroy',
        'as'   => 'sponsors.destroy']);
});


/**
* Volunteers Route
**/
Route::group(['prefix' => '/'],function(){
    
    Route::resource('volunteers','VolunteersController', [
        'only' => ['index', 'store', 'create', 'destroy', 'show','edit']]);
    Route::post('volunteers/{id}', [
        'uses' => 'VolunteersController@update',
        'as'   => 'volunteers.update']);
    Route::get('volunteers/{id}/destroy', [
        'uses' => 'VolunteersController@destroy',
        'as'   => 'volunteers.destroy']);
});

/**
* Images Routes
**/
Route::group(['prefix' => '/'], function(){

    Route::resource('images', 'ImagesController',[
        'only' => ['show', 'destroy']]);
    Route::get('images/{id}/destroy', [
        'uses' => 'ImagesController@destroy',
        'as'   => 'images.destroy']);
});


/**
* Auth Route for admin login
**/
Auth::routes();

/*
Route::group(['prefix' => 'admin'],function(){
    
        Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
        Route::post('authenticate', [
            'uses' => 'AuthenticateController@authenticate',
            'as'   => 'authenticate.auth']);
    
});*/

/**
* Contact Route
**/
Route::group(['prefix' => 'contact'], function(){
    Route::post('/', [
        'uses' => 'ContactController@store',
        'as'   => 'contact']);
});

/*
* Image upload Route
**/
Route::post('/uploadevent', 'UploadController@Event');
Route::post('/uploadvolunteer', 'UploadController@Volunteer');
Route::post('/uploadsponsor', 'UploadController@Sponsor');
Route::post('/uploadshow', 'UploadController@Show');
Route::post('/uploadhomeimage',[
    'uses' => 'UploadController@Home',
    'as'   => 'homeimg']);




