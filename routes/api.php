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

Route::middleware('auth:api')->group(function () {
    Route::resource('screens', 'Api\ScreenController');
    // Need to add all functionailty to support notes.
    // Route::resource('notes', 'Api\NoteController');
    Route::post('screens/email/{id}', 'Api\ScreenController@send');
    Route::get('me', 'Api\AuthController@getUser');
});

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
