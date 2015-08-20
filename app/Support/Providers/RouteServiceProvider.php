<?php namespace BibleBowl\Support\Providers;

use BibleBowl\Role;
use Entrust;
use Route;
use Auth;
use Redirect;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'BibleBowl\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		//
		
		parent::boot($router);
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function(Router $router)
		{
			# Default Routes for different users
			Route::get('/', function () {
				if (Auth::guest()) {
					return Redirect::to('login');
				}

				return Redirect::to('dashboard');
			});

			# Authentication Routes
			Route::get('login', 'Auth\AuthController@getLogin');
			Route::get('login/{provider}', 'Auth\ThirdPartyAuthController@login');
			Route::post('login', 'Auth\AuthController@postLogin');
			Route::get('register/confirm/{guid}', 'Auth\ConfirmationController@getConfirm');
			Route::get('register', 'Auth\AuthController@getRegister');
			Route::post('register', 'Auth\AuthController@postRegister');
			Route::controllers([
				'password' => 'Auth\PasswordController',
			]);

			# Must be logged in to access these routes
			Route::group(['middleware' => 'auth'], function () {
				Route::get('logout', 'Auth\AuthController@getLogout');

				Route::get('dashboard', 'DashboardController@index');

                Route::group([
                    'prefix'	=> 'account',
                    'namespace'	=> 'Account'
                ], function () {
                    Route::resource('address', 'AddressController');
                    Route::get('address/{address}/makePrimary', 'AddressController@setPrimaryAddressId');
                    Route::get('setup', 'SetupController@getSetup');
                    Route::post('setup', 'SetupController@postSetup');
                    Route::get('edit', 'AccountController@edit');
                    Route::patch('update', 'AccountController@update');
                });

                Route::resource('player', 'PlayerController', [
                    'except' => ['delete']
                ]);

                Route::group([
                    'namespace'	=> 'Seasons'
                ], function () {
					Route::get('register/search/group', 'PlayerRegistrationController@findGroupToRegister');
                    Route::get('register/group/{group?}', 'PlayerRegistrationController@getRegister');
					Route::post('register/group/{group?}', 'PlayerRegistrationController@postRegister');
					Route::get('register/{player}/edit', 'PlayerRegistrationController@getRegisterEdit');
					Route::patch('register/{player}/edit', 'PlayerRegistrationController@postRegisterEdit');

					Route::get('join/search/group', 'PlayerRegistrationController@findGroupToJoin');
					Route::get('join/group/{group}', 'PlayerRegistrationController@getJoin');
					Route::post('join/group/{group}', 'PlayerRegistrationController@postJoin');
                });

                Route::resource('group', 'GroupController', [
                    'except' => ['delete']
                ]);
				Route::get('group/{group}/swap', 'GroupController@swap');

				# Roster
				Entrust::routeNeedsRole('roster/*', [Role::HEAD_COACH]);
				Route::get('roster', 'Groups\RosterController@index');
				Route::get('roster/inactive', 'Groups\RosterController@inactive');
				Route::get('roster/export', 'Groups\RosterController@export');
				Route::get('player/{player}/activate', 'Groups\PlayerController@activate');
				Route::get('player/{player}/deactivate', 'Groups\PlayerController@deactivate');
			});

		});


	}

}
