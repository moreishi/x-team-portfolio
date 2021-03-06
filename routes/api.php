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

/** Public */
Route::post('login','Api\PassportController@login');
Route::post('register','Api\PassportController@register');

Route::middleware('auth:api')->group(function() {

    Route::prefix('companies')->group(function() {
        Route::get('{id}/jobs','Api\CompanyController@jobs');
    });

    Route::resource('companies','Api\CompanyController');
    Route::resource('jobs','Api\JobController');
    Route::resource('users','Api\UserController');
});