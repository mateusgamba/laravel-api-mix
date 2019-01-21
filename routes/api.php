<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'prefix' => 'auth',
    'namespace' => 'Api'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::get('activate/{code}', 'AuthController@activate');

    Route::group(['middleware' => ['auth', 'jwt.refresh']], function() {
        Route::get('me', 'AuthController@me');
    });
});

Route::group([
    'prefix' => 'password',
    'namespace' => 'Api'
], function () {
    Route::post('forgot', 'PasswordResetController@forgot');
    Route::post('reset', 'PasswordResetController@reset');
    Route::get('verification/{token}', 'PasswordResetController@verification');
});

Route::get('/', function() {
    return response()->json([
        'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
    ]);
});

//Route::resource('clients', 'ClientController', ['except' => ['create', 'edit']]);
