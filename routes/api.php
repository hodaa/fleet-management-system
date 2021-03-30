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
Route::post('v1/register', 'AuthController@register');
Route::post('v1/login', 'AuthController@login');

Route::middleware('auth:api')->get('/v1/trips', 'TripController@index');
Route::middleware('auth:api')->post('/v1/trip/book', 'TripController@book');
