<?php

use Illuminate\Http\Request;
use App\Http\Middleware\ClientCan;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'v1',
    'middleware' => ['cors']
], function () {
    Route::group([
        'prefix' => 'oauth',
    ], function () {
        Route::post('token', 'OauthController@token');
    });

    Route::get('clients/{limitNumber?}/{pageNumber?}', 'ClientController@getList')->middleware(ClientCan::class);
    Route::group([
        'prefix' => 'client',
    ], function () {
        Route::post('create', 'ClientController@addClient')->middleware(ClientCan::class);
        Route::post('revoke', 'ClientController@revokeClient')->middleware(ClientCan::class);
    });
    Route::group([
        'prefix' => 'currency',
    ], function () {
        Route::post('create', 'CurrenciesController@create')->middleware(ClientCan::class);
        Route::post('update', 'CurrenciesController@update')->middleware(ClientCan::class);
        Route::get('list', 'CurrenciesController@list')->middleware(ClientCan::class);
    });
    Route::group([
        'prefix' => 'wallet',
    ], function () {
        Route::post('credit', 'BalancesController@credit')->middleware(ClientCan::class);
        Route::post('debit', 'BalancesController@debit')->middleware(ClientCan::class);
        Route::get('balance', 'BalancesController@balance')->middleware(ClientCan::class);
        Route::get('balance/batch', 'BalancesController@batch')->middleware(ClientCan::class);
        Route::get('transaction', 'TransactionsController@transactions')->middleware(ClientCan::class);
    });
});
