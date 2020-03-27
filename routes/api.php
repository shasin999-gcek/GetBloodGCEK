<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/user')->group( function() {
    Route::post('/login', 'LoginController@login');
    Route::middleware('auth:api')->get('/all', 'UserController@index');
    Route::middleware('auth:api')->post('/create', 'UserController@store');
    Route::middleware('auth:api')->get('/show/{user}', 'UserController@show');
    Route::middleware('auth:api')->post('/delete/{user}', 'UserController@destroy');
});

Route::prefix('/password')->group( function() {
    Route::post('/reset', 'ResetPasswordController@resetpassword');
    Route::post('/update', 'ResetPasswordController@updatepassword')->name('password.update');
    Route::post('/verify', 'ResetPasswordController@verifyOTP');
});


Route::prefix('/donar')->middleware('auth:api')->group( function() {
    Route::get('/all', 'DonarController@index');
    Route::get('/{id}', 'DonarController@show');
    Route::post('/create', 'DonarController@store');
    Route::post('/lastdonation', 'DonarController@setLastDonationDate');
    Route::post('/delete', 'DonarController@delete');
});




