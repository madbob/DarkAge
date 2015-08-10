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

Route::get('/', 'HomeController@index');
Route::post('/startup', 'HomeController@startup');
Route::post('/init', 'HomeController@init');
Route::get('/download/{id}', 'HomeController@download');
Route::get('/vote/{id}', 'HomeController@vote');
Route::get('/unvote/{id}', 'HomeController@unvote');
Route::get('/{username}/{year}', 'HomeController@userpage');
