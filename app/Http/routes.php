<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@indexList');

// user resources
Route::post('users', 'UsersController@createUser');
Route::get('users', 'UsersController@getUsers');
Route::get('users/{username}', 'UsersController@getUserProps');
Route::patch('users/{username}', 'UsersController@updateUser');
Route::delete('users/{username}', 'UsersController@deleteUser');

// messages resources
Route::get('users/{username}/messages', 'MessagesController@getUserMessages');
Route::post('users/{username}/messages', 'MessagesController@newMessage');
Route::get('users/{username}/messages/{contact}', 'MessagesController@getMessagesFromContact');