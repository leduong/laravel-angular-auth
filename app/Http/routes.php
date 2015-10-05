<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	return view('welcome');
});

Route::post('auth','Auth\AuthController@login');
Route::post('register','Auth\AuthController@create');

Route::group(array(
	'prefix' => 'api/user',
	'middleware' => 'token'
), function () {
	Route::get('', array(
		'uses' => 'UserController@index'
	));

	Route::get('{id}', array(
		'uses' => 'UserController@show'
	))->where(array('id' => '[0-9]+'));


	Route::post('', array(
		'uses' => 'UserController@store'
	))->where(array('id' => '[0-9]+'));

	Route::put('{id}', array(
		'uses' => 'UserController@update'
	));
	
	Route::delete('{id}', array(
		'before' => 'api.admin',
		'uses' => 'UserController@destroy'
	))->where(array('id' => '[0-9]+'));

});


Route::group(array(
	'prefix' => 'api/location',
	'middleware' => 'token'
), function () {
	Route::get('', array(
		'before' => 'api.admin',
		'uses' => 'UserLocationController@index'
	));

	Route::get('{id}', array(
		'uses' => 'UserLocationController@show'
	))->where(array('id' => '[0-9]+'));


	Route::post('', array(
		'before' => 'api.admin',
		'uses' => 'UserLocationController@create'
	))->where(array('id' => '[0-9]+'));

	Route::put('{id}', array(
		'uses' => 'UserLocationController@update'
	));
	
	Route::delete('{id}', array(
		'before' => 'api.admin',
		'uses' => 'UserLocationController@destroy'
	))->where(array('id' => '[0-9]+'));

});