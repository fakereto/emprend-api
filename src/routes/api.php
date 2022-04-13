<?php

use Illuminate\Http\Request;
Use App\Http\Controllers\CommentController;
Use App\Http\Controllers\Auth\RegisterController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('comments', 'CommentController@index');
    Route::get('comments/{id}', 'CommentController@show');
    Route::post('comments', 'CommentController@store');
    Route::put('comments/{id}', 'CommentController@update');
    Route::delete('comments/{id}', 'CommentController@delete');

    Route::get('user/', 'UserController@index');
    Route::get('user/{id}', 'UserController@show');
    Route::get('user-comments', 'UserController@userComments');
});

//Auth Methods for api
Route::post('register', 'Auth\RegisterController@create');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');
