<?php

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

Route::post('/register', 'App\Http\Controllers\JwtAuthController@register');
Route::post('/login', 'App\Http\Controllers\JwtAuthController@login');
Route::post('/signout', 'App\Http\Controllers\JwtAuthController@signout');
Route::post('/forgotPassword', 'App\Http\Controllers\JwtAuthController@forgotPassword');
Route::post('/resetPassword', 'App\Http\Controllers\JwtAuthController@resetPassword');


Route::post('/addNotes','App\Http\Controllers\NoteController@createNote');
Route::get('/getNotes','App\Http\Controllers\NoteController@getNotes');
Route::post('/updateNote','App\Http\Controllers\NoteController@updateNote');
Route::post('/deleteNote','App\Http\Controllers\NoteController@deleteNote');






