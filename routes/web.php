<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('login/twitch', 'Auth\LoginController@redirectToProvider');
Route::get('login/twitch/callback', 'Auth\LoginController@handleProviderCallback');