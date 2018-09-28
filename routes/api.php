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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pastmessage/{pid}', [
    'as' => 'pastmessage',
    'uses' => 'messageController@retrieveLastMessage',
]);

Route::get('/postmessage/{pid}/{mesage}/{value}', [
    'as' => 'postmessage',
    'uses' => 'messageController@LastMessage',
]);

Route::get('/push/{pid}/{mesage}/{amount}', [
    'as' => 'push',
    'uses' => 'messageController@push',
]);

Route::get('/acc/{pid}/', [
    'as' => 'push',
    'uses' => 'messageController@push',
]);

Route::get('/register/{pid}/{phone}', [
    'as' => 'register',
    'uses' => 'messageController@register',
]);
Route::get('/link/{pid}/{phone}', [
    'as' => 'register',
    'uses' => 'messageController@link',
]);

Route::get('/otp/{pid}/{otp}', [
    'as' => 'otp',
    'uses' => 'messageController@confirmOtp',
]);

Route::get('/balance/{pid}', [
    'as' => 'balance',
    'uses' => 'messageController@balance',
]);

Route::get('/ministatement/{pid}', [
    'as' => 'min',
    'uses' => 'messageController@ministatement',
]);

Route::get('/push1/{pid}/', [
    'as' => 'push1',
    'uses' => 'messageController@pushAcc',
]);

Route::get('/push2/{pid}/{amount}', [
    'as' => 'push2',
    'uses' => 'messageController@push2',
]);

