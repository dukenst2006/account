<?php namespace BibleBowl\Support\Providers;

use Auth;
use BibleBowl\Permission;
use BibleBowl\Role;
use Entrust;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Redirect;
use Route;

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

                    Route::get('notifications', 'NotificationController@edit');
                    Route::patch('notifications', 'NotificationController@update');
                });

				Route::resource('player', 'PlayerController', [
					'except' => ['delete']
				]);

				Route::group([
					'namespace'	=> 'Teams'
				], function () {
					Route::resource('team', 'TeamSetController', [
						'only' => ['show', 'index', 'create', 'store']
					]);
					Route::get('team/{id}/download', 'TeamSetController@download');
				});

                Route::group([
                    'namespace'	=> 'Seasons'
                ], function () {
                    # action = join|register
                    Route::get('{action}/program', 'PlayerRegistrationController@program');

					Route::get('register/{programSlug}/search/group', 'PlayerRegistrationController@findGroupToRegister');
                    Route::get('register/{programSlug}/group/{group?}', 'PlayerRegistrationController@getRegister');
					Route::post('register/{programSlug}/group/{group?}', 'PlayerRegistrationController@postRegister');

					Route::get('join/{programSlug}/search/group', 'PlayerRegistrationController@findGroupToJoin');
					Route::get('join/{programSlug}/group/{group}', 'PlayerRegistrationController@getJoin');
					Route::post('join/{programSlug}/group/{group}', 'PlayerRegistrationController@postJoin');

					# the group's registration link
					Route::get('group/{guid}/register', 'PlayerRegistrationController@rememberGroup');
                });

                Route::resource('group', 'GroupController', [
                    'except' => ['delete']
                ]);
                Route::get('group/create/search', 'GroupController@searchBeforeCreate');
				Route::get('group/{group}/swap', 'GroupController@swap');

				# Roster
				//Entrust::routeNeedsRole('roster/*', [Role::HEAD_COACH]);
				Route::get('roster', 'Groups\RosterController@index');
				Route::get('roster/inactive', 'Groups\RosterController@inactive');
                Route::get('roster/export', 'Groups\RosterController@export');
                Route::get('roster/map', 'Groups\RosterController@map');
				Route::get('player/{player}/activate', 'Groups\PlayerController@activate');
				Route::get('player/{player}/deactivate', 'Groups\PlayerController@deactivate');

                # ------------------------------------------------
                # Admin Routes
                # ------------------------------------------------
                Route::group([
                    'prefix'	=> 'admin',
                    'namespace'	=> 'Admin'
                ], function () {
                    Entrust::routeNeedsPermission('reports/*', [Permission::VIEW_REPORTS]);
                    Route::controller('reports', 'ReportsController');

                    Entrust::routeNeedsRole('admin/players/*', [Role::DIRECTOR]);
                    Route::get('players', 'PlayerController@index');
                    Route::get('players/{playerId}', 'PlayerController@show');

                    Entrust::routeNeedsRole('admin/groups/*', [Role::DIRECTOR]);
                    Route::get('groups', 'GroupController@index');
                    Route::get('groups/{groupId}', 'GroupController@show');

                    Entrust::routeNeedsRole('admin/users/*', [Role::DIRECTOR]);
                    Route::get('users', 'UserController@index');
                    Route::get('users/{userId}', 'UserController@show');

					Route::resource('tournaments', 'TournamentsController');
					Route::resource('tournaments.events', 'Tournaments\EventsController', [
						'except' => ['index', 'show']
					]);
                });
			});

            # legal
            Route::get('terms-of-use', function () {
                return view('legal/terms-of-use');
            });
            Route::get('privacy-policy', function () {
                return view('legal/privacy-policy');
            });

		});


	}

}
