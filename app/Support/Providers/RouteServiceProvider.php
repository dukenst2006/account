<?php namespace BibleBowl\Support\Providers;

use BibleBowl\Ability;
use BibleBowl\Role;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Redirect;
use Route;

class RouteServiceProvider extends ServiceProvider
{

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
        $router->group(['namespace' => $this->namespace], function (Router $router) {
            Route::auth();

            # Default Routes for different users
            Route::get('/', 'DashboardController@root');

            # Authentication Routes
            Route::get('login', 'Auth\AuthController@getLogin');
            Route::get('login/{provider}', 'Auth\ThirdPartyAuthController@processLogin');
            Route::post('login', 'Auth\AuthController@postLogin');
            Route::get('register/confirm/{guid}', 'Auth\ConfirmationController@getConfirm');
            Route::get('register', 'Auth\AuthController@getRegister');
            Route::post('register', 'Auth\AuthController@postRegister');

            // Password Reset Routes...
            Route::get('password/reset', 'Auth\PasswordController@reset');
            Route::post('password/email', 'Auth\PasswordController@postEmail');
            Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
            Route::post('password/reset', 'Auth\PasswordController@reset');

            // Tournament routes
            Route::group([
                'prefix'    => 'tournaments'
            ], function () {
                Route::get('{slug}', 'TournamentsController@show');
            });
            
            Route::get('cart', 'ShopController@viewCart');
            Route::post('cart', 'ShopController@processPayment');

            # the group's registration link
            Route::get('group/{guid}/register', 'Seasons\PlayerRegistrationController@rememberGroup');

            Route::get('invitation/{guid}/{action}', 'InvitationController@claim');

            Route::group([
                'prefix'    => 'tournaments/{slug}',
                'namespace' => 'Tournaments'
            ], function () {
                Route::group([
                    'prefix'    => 'registration',
                    'namespace' => 'Registration'
                ], function () {
                    // spectators
                    Route::get('spectator', 'SpectatorController@getRegistration');
                    Route::post('standalone-spectator', 'SpectatorController@postStandaloneRegistration');
                    Route::post('spectator', 'SpectatorController@postRegistration');
                });
            });

            Route::get('cart', 'ShopController@viewCart');
            Route::post('cart', 'ShopController@processPayment');


            # Must be logged in to access these routes
            Route::group(['middleware' => 'auth'], function () {
                Route::get('logout', 'Auth\AuthController@getLogout');

                Route::get('dashboard', 'DashboardController@index');

                Route::group([
                    'prefix'    => 'account',
                    'namespace'     => 'Account'
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
                    'namespace'    => 'Teams',
                    'middleware' => ['can:'.Ability::MANAGE_TEAMS]
                ], function () {
                    Route::resource('teamsets', 'TeamSetController');
                    Route::get('teamsets/{teamsets}/pdf', 'TeamSetController@pdf');
                    Route::post('teamsets/{teamsets}/createTeam', 'TeamController@store');

                    Route::resource('teams', 'TeamController', [
                        'only' => ['update', 'destroy']
                    ]);

                    Route::post('teams/{teams}/addPlayer', 'TeamController@addPlayer');
                    Route::post('teams/{teams}/removePlayer', 'TeamController@removePlayer');
                    Route::post('teams/{teams}/updateOrder', 'TeamController@updateOrder');
                });

                Route::group([
                    'namespace'    => 'Seasons'
                ], function () {
                    Route::get('players/pay', 'GroupRegistrationController@getPayPlayerRegistration');
                    Route::post('players/pay', 'GroupRegistrationController@postPayPlayerRegistration');

                    Route::get('register/players', 'PlayerRegistrationController@getPlayers');
                    Route::post('register/players', 'PlayerRegistrationController@postPlayers');
                    Route::get('register/summary', 'PlayerRegistrationController@summary');
                    Route::get('register/{programSlug}/group/{group?}', 'PlayerRegistrationController@chooseGroup');
                    Route::get('register/{programSlug}/search/group', 'PlayerRegistrationController@findGroupToRegister');
                    Route::post('register/{programSlug}/group/{group?}', 'PlayerRegistrationController@postRegister');
                    Route::get('register/{programSlug}/later', 'PlayerRegistrationController@later');
                    Route::post('register/submit', 'PlayerRegistrationController@submit');
                    Route::get('register/program', 'PlayerRegistrationController@getChooseProgram');
                    Route::post('register/program', 'PlayerRegistrationController@postChooseProgram');
                });

                # group routes
                Route::resource('group', 'GroupController', [
                    'except' => ['delete']
                ]);
                Route::group([
                    'prefix'    => 'group/{group}/settings',
                    'namespace'    => 'Groups'
                ], function () {
                    Route::get('email', 'SettingsController@editEmail');
                    Route::post('email', 'SettingsController@postEmail');
                    Route::post('test-email', 'SettingsController@sendTestEmail');
                    Route::get('users', 'SettingsController@listUsers');
                    Route::get('users/invite', 'SettingsController@getUserInvite');
                    Route::post('users/invite', 'SettingsController@sendUserInvite');
                    Route::get('users/invite/{invitationId}/retract', 'SettingsController@retractInvite');
                    Route::get('users/{userId}/remove', 'SettingsController@removeUser');
                    Route::get('integrations', 'SettingsController@editIntegrations');
                    Route::post('integrations', 'SettingsController@postIntegrations');
                });

                Route::get('group/create/search', 'GroupController@searchBeforeCreate');
                Route::get('group/{group}/swap', 'GroupController@swap');

                # Roster
                Route::group([
                    'middleware' => ['can:'.Ability::MANAGE_ROSTER]
                ], function () {
                    Route::get('roster', 'Groups\RosterController@index');
                    Route::get('roster/inactive', 'Groups\RosterController@inactive');
                    Route::get('roster/export', 'Groups\RosterController@export');
                    Route::get('roster/map', 'Groups\RosterController@map');
                    Route::get('guardian/{guardian}', 'Groups\GuardianController@show');
                    Route::get('player/{player}/activate', 'Groups\PlayerController@activate');
                    Route::get('player/{player}/deactivate', 'Groups\PlayerController@deactivate');
                });

                Route::group([
                    'prefix'        => 'admin',
                    'middleware'    => ['can:'.Ability::CREATE_TOURNAMENTS],
                    'namespace'     => 'Tournaments\Admin'
                ], function () {
                    Route::resource('tournaments', 'TournamentsController');
                    Route::resource('tournaments.events', 'EventsController', [
                        'except' => ['index', 'show']
                    ]);
                });

                Route::group([
                    'prefix'    => 'tournaments/{slug}',
                    'namespace' => 'Tournaments'
                ], function () {
                    Route::group([
                        'prefix'    => 'registration',
                        'namespace' => 'Registration'
                    ], function () {
                        // quizmasters
                        Route::get('quizmaster', 'QuizmasterController@getRegistration');
                        Route::post('standalone-quizmaster', 'QuizmasterController@postStandaloneRegistration');
                        Route::post('quizmaster', 'QuizmasterController@postRegistration');


                        Route::get('quizmaster-preferences/{guid}', 'QuizmasterController@getPreferences');
                        Route::post('quizmaster-preferences/{guid}', 'QuizmasterController@postPreferences');

                        // groups
                        Route::get('group/choose-teams', 'GroupController@chooseTeams');
                        Route::get('/group/teams/{teamSet}', 'GroupController@setTeamSet');
                        Route::get('group/quizmasters', 'GroupController@quizmasters');
                    });

                    Route::get('group', 'Registration\GroupController@index');
                });

                # ------------------------------------------------
                # Admin Routes
                # ------------------------------------------------
                Route::group([
                    'prefix'    => 'admin',
                    'namespace'     => 'Admin'
                ], function () {

                    Route::group([
                        'middleware' => ['can:'.Ability::MANAGE_GROUPS]
                    ], function () {
                        Route::get('groups', 'GroupController@index');
                        Route::get('groups/outstanding-registration-fees', 'GroupController@outstandingRegistrationFees');
                        Route::get('groups/{groupId}', 'GroupController@show');
                        Route::get('groups/{groupId}/transfer-ownership', 'GroupController@getTransferOwnership');
                        Route::post('groups/{groupId}/transfer-ownership', 'GroupController@postTransferOwnership');
                    });

                    Route::group([
                        'middleware' => ['can:'.Ability::MANAGE_PLAYERS]
                    ], function () {
                        Route::get('players', 'PlayerController@index');
                        Route::get('players/{playerId}', 'PlayerController@show');
                        Route::delete('players/{playerId}', 'PlayerController@destroy');
                    });

                    Route::group([
                        'middleware' => ['can:'.Ability::MANAGE_USERS]
                    ], function () {
                        Route::get('users', 'UserController@index');
                        Route::get('users/{userId}', 'UserController@show');
                        Route::get('users/{userId}/roles', 'UserController@roles');
                        Route::post('users/{userId}/roles', 'UserController@updateRoles');
                    });

                    Route::group([
                        'middleware' => ['can:'.Ability::SWITCH_ACCOUNTS]
                    ], function () {
                        Route::get('switchUser/{userId}', 'UserController@switchUser');
                    });

                    Route::group([
                        'middleware' => ['can:'.Ability::VIEW_REPORTS]
                    ], function () {
                        Route::get('reports/growth', 'ReportsController@getGrowth');
                        Route::get('reports/seasons', 'ReportsController@getSeason');
                        Route::get('reports/registration-surveys', 'ReportsController@getRegistrationSurveys');
                    });

                    Route::group([
                        'middleware' => ['can:'.Ability::MANAGE_SETTINGS]
                    ], function () {
                        Route::get('settings', 'SettingsController@edit');
                        Route::patch('settings', 'SettingsController@update');
                    });
                });
            });
        });

        Route::get('faq', function () {
            return view('faq');
        });

        Route::get('healthcheck/{token}', function ($token) {
            if ($token == env('HEALTHCHECK_TOKEN')) {
                $connection = DB::connection();
                $connection->disconnect();
                return response();
            } else {
                throw new \Exception('Invalid healthcheck token');
            }
        });
    }
}
