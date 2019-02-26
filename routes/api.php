<?php

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

use App\Http\Middleware\TwitchOauthMiddleware;

Route::get('posts/{post}/comments', 'CommentController@index');

Route::get('twitch/users', 'TwitchUserController@index');
Route::get('twitch/users/name', 'TwitchUserController@searchUser');
Route::post('twitch/subscribe', 'TwitchUserController@subscribeToAUser');
Route::post('twitch/follow', 'TwitchUserController@followUser');
Route::any('twitch/notify/callback', 'NotificationsController@notificationCallback');

Route::middleware(TwitchOauthMiddleware::class)->group(
    function () {
        //Route::get('twitch/users', 'TwitchUserController@index');
    }
);

Route::middleware('auth:api')->group(
    function () {
        Route::post('posts/{post}/comment', 'CommentController@store');
});

