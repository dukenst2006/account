<?php

use BibleBowl\Ability;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::auth();

// Default Routes for different users
Route::get('/', 'DashboardController@root');

// Authentication Routes
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');

Route::get('login/{provider}', 'Auth\ThirdPartyAuthController@processLogin');

// Registration Routes
Route::get('register/confirm/{guid}', 'Auth\ConfirmationController@getConfirm');
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Tournament routes
Route::group([
    'prefix'    => 'tournaments',
], function () {
    Route::get('{slug}', 'TournamentsController@show');
});

Route::get('cart', 'ShopController@viewCart');
Route::post('cart', 'ShopController@processPayment');

// the group's registration link
Route::get('group/{guid}/register', 'Seasons\PlayerRegistrationController@rememberGroup');

Route::get('invitation/{guid}/{action}', 'InvitationController@claim');

Route::group([
    'prefix'    => 'tournaments/{slug}',
    'namespace' => 'Tournaments',
], function () {
    Route::group([
        'prefix'    => 'registration',
        'namespace' => 'Registration',
    ], function () {
        // spectators
        Route::get('spectator', 'SpectatorController@getRegistration');
        Route::post('standalone-spectator', 'SpectatorController@postStandaloneRegistration');
        Route::post('spectator', 'SpectatorController@postRegistration');
    });
});

Route::get('cart', 'ShopController@viewCart');
Route::post('cart', 'ShopController@processPayment');

// Must be logged in to access these routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', 'DashboardController@index');

    Route::group([
        'prefix'        => 'account',
        'namespace'     => 'Account',
    ], function () {
        Route::resource('receipts', 'ReceiptController', [
            'only' => ['index', 'show'],
        ]);

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
        'except' => ['delete'],
    ]);

    Route::group([
        'namespace'    => 'Teams',
        'middleware'   => ['can:'.Ability::MANAGE_TEAMS],
    ], function () {
        Route::resource('teamsets', 'TeamSetController');
        Route::get('teamsets/{teamset}/pdf', 'TeamSetController@pdf');
        Route::post('teamsets/{teamset}/createTeam', 'TeamController@store');

        Route::resource('teams', 'TeamController', [
            'only' => ['update', 'destroy'],
        ]);

        Route::post('teams/{team}/addPlayer', 'TeamController@addPlayer');
        Route::post('teams/{team}/removePlayer', 'TeamController@removePlayer');
        Route::post('teams/{team}/updateOrder', 'TeamController@updateOrder');
    });

    Route::group([
        'namespace'    => 'Seasons',
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

    // group routes
    Route::resource('group', 'GroupController', [
        'except' => ['delete'],
    ]);
    Route::group([
        'prefix'       => 'group/{group}/settings',
        'namespace'    => 'Groups',
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

    // Roster
    Route::group([
        'middleware' => ['can:'.Ability::MANAGE_ROSTER],
    ], function () {
        Route::get('roster', 'Groups\RosterController@index');
        Route::get('roster/inactive', 'Groups\RosterController@inactive');
        Route::get('roster/export', 'Groups\RosterController@export');
        Route::get('roster/map', 'Groups\RosterController@map');
        Route::get('guardian/{guardian}', 'Groups\GuardianController@show');
        Route::get('player/{player}/activate', 'Groups\PlayerController@activate');
        Route::get('player/{player}/deactivate', 'Groups\PlayerController@deactivate');

        Route::get('memory-master', 'Groups\MemoryMasterController@showAchievers');
        Route::post('memory-master', 'Groups\MemoryMasterController@updateAchievers');
    });

    Route::group([
        'as'            => 'admin.',
        'prefix'        => 'admin',
        'middleware'    => ['can:'.Ability::CREATE_TOURNAMENTS],
        'namespace'     => 'Tournaments\Admin',
    ], function () {
        Route::resource('tournaments', 'TournamentsController');
        Route::resource('tournaments.events', 'EventsController', [
            'except' => ['index', 'show'],
        ]);
        Route::get('tournaments/{tournamentId}/events/{eventId}/participants/export/{format}', 'EventsController@exportParticipants');
        Route::get('tournaments/{tournamentId}/participants/teams/export/{format}', 'TournamentsController@exportTeams');
        Route::get('tournaments/{tournamentId}/participants/players/export/{format}', 'TournamentsController@exportPlayers');
        Route::get('tournaments/{tournamentId}/participants/quizmasters/export/{format}', 'TournamentsController@exportQuizmasters');
        Route::get('tournaments/{tournamentId}/participants/tshirts/export/{format}', 'TournamentsController@exportTshirts');
        Route::get('tournaments/{tournamentId}/participants/spectators/export/{format}', 'TournamentsController@exportSpectators');
    });

    Route::group([
        'prefix'    => 'tournaments/{slug}',
        'namespace' => 'Tournaments',
    ], function () {
        Route::group([
            'prefix'    => 'registration',
            'namespace' => 'Registration',
        ], function () {
            // quizmasters
            Route::get('quizmaster', 'QuizmasterController@getRegistration');
            Route::post('standalone-quizmaster', 'QuizmasterController@postStandaloneRegistration');
            Route::post('quizmaster', 'QuizmasterController@postRegistration');
            Route::delete('quizmaster/{guid}', 'QuizmasterController@deleteRegistration');

            Route::get('quizmaster-preferences/{guid}', 'QuizmasterController@getPreferences');
            Route::post('quizmaster-preferences/{guid}', 'QuizmasterController@postPreferences');

            // spectators
            Route::delete('spectator/{guid}', 'SpectatorController@deleteRegistration');

            // groups
            Route::get('group/teams/new', 'GroupController@newTeamSet');
            Route::get('group/choose-teams', 'GroupController@chooseTeams');
            Route::get('group/teams/{teamSet}', 'GroupController@setTeamSet');
            Route::get('group/quizmasters', 'GroupController@quizmasters');
            Route::get('group/events', 'GroupController@events');
            Route::post('group/events', 'GroupController@postEvents');
            Route::get('group/pay', 'GroupController@pay');
            Route::post('group/pay', 'GroupController@postPay');
        });

        Route::get('group', 'Registration\GroupController@index');
    });

    // ------------------------------------------------
    // Admin Routes
    // ------------------------------------------------
    Route::group([
        'prefix'        => 'admin',
        'namespace'     => 'Admin',
    ], function () {
        Route::group([
            'middleware' => ['can:'.Ability::MANAGE_GROUPS],
        ], function () {
            Route::get('groups', 'GroupController@index');
            Route::get('groups/outstanding-registration-fees', 'GroupController@outstandingRegistrationFees');
            Route::get('groups/{groupId}', 'GroupController@show');
            Route::get('groups/{groupId}/transfer-ownership', 'GroupController@getTransferOwnership');
            Route::post('groups/{groupId}/transfer-ownership', 'GroupController@postTransferOwnership');
        });

        Route::group([
            'middleware' => ['can:'.Ability::MANAGE_PLAYERS],
        ], function () {
            Route::get('players', 'PlayerController@index');
            Route::get('players/export/{format}', 'PlayerController@export');
            Route::get('players/{playerId}', 'PlayerController@show');
            Route::delete('players/{playerId}', 'PlayerController@destroy');
        });

        Route::group([
            'middleware' => ['can:'.Ability::MANAGE_USERS],
        ], function () {
            Route::get('users', 'UserController@index');
            Route::get('users/{userId}', 'UserController@show');
            Route::get('users/{userId}/roles', 'UserController@roles');
            Route::post('users/{userId}/roles', 'UserController@updateRoles');
        });

        Route::group([
            'middleware' => ['can:'.Ability::SWITCH_ACCOUNTS],
        ], function () {
            Route::get('switchUser/{userId}', 'UserController@switchUser');
        });

        Route::group([
            'middleware' => ['can:'.Ability::VIEW_REPORTS],
        ], function () {
            Route::get('reports/growth', 'ReportsController@getGrowth');
            Route::get('reports/seasons', 'ReportsController@getSeason');
            Route::get('reports/export-memory-master', 'ReportsController@exportMemoryMaster');
            Route::get('reports/financials', 'ReportsController@getFinancials');
            Route::get('reports/registration-surveys', 'ReportsController@getRegistrationSurveys');
        });

        Route::group([
            'middleware' => ['can:'.Ability::MANAGE_SETTINGS],
        ], function () {
            Route::get('settings', 'SettingsController@edit');
            Route::patch('settings', 'SettingsController@update');
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
