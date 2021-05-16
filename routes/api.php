<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
	return response()->json(["status" => "it works !"]);
});

Route::group(['namespace' => 'Auth'], function () {
	Route::post('login', 'AuthController@login');

	Route::group(['middleware' => 'jwt'], function () {
		Route::post('logout', 'AuthController@logout');
		Route::post('refresh', 'AuthController@refresh');
		Route::post('me', 'AuthController@me');
	});
});
