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

Route::get('/', function()
{
    return redirect('home/');
});

Route::get('home/{path?}', 'HomeController@index')->where('path', '(.*)'); // List
Route::put('manager/put/directory', 'HomeController@create'); // Create a new directory
Route::put('manager/put/file', 'HomeController@upload'); // Upload a file
Route::post('manager/move', 'HomeController@move'); // Move or rename an object
Route::delete('manager/delete', 'HomeController@delete'); // Delete an object

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
