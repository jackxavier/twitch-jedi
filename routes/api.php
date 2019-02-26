<?php


Route::get('twitch/users', 'TwitchUserController@index');
Route::get('twitch/users/name', 'TwitchUserController@searchUser');
Route::post('twitch/subscribe', 'TwitchUserController@subscribeToAUser');
Route::post('twitch/follow', 'TwitchUserController@followUser');
Route::any('twitch/notify/callback', 'NotificationsController@notificationCallback');



