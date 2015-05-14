<?php

# Default Routes for different users
Route::get('/', function () {
	if (Auth::guest()) {
		return Redirect::to('login');
	}

	return Redirect::to('dashboard');
});

# Authentication Routes
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');
Route::get('register', 'Auth\AuthController@getRegister');
Route::post('register', 'Auth\AuthController@postRegister');
Route::controllers([
	'password' => 'Auth\PasswordController',
]);

Route::get('dashboard', 'DashboardController@index');
