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

/**
 * routes for JWTAuth controller
 */
Route::post('/register', 'App\Http\Controllers\JwtAuthController@register');
Route::post('/login', 'App\Http\Controllers\JwtAuthController@login');
Route::post('/signout', 'App\Http\Controllers\JwtAuthController@signout');
Route::post('/forgotPassword', 'App\Http\Controllers\JwtAuthController@forgotPassword');
Route::post('/resetPassword', 'App\Http\Controllers\JwtAuthController@resetPassword');


/**
 * routes for Note controller
 */
Route::post('/addNotes','App\Http\Controllers\NoteController@createNote');
Route::get('/getNotes','App\Http\Controllers\NoteController@getNotes');
Route::post('/updateNote','App\Http\Controllers\NoteController@updateNote');
Route::post('/deleteNote','App\Http\Controllers\NoteController@deleteNote');

/**
 * routes for Label controller
 */
Route::post('/makeLabel', 'App\Http\Controllers\LabelController@createLabel');
Route::post('/noteToLabel', 'App\Http\Controllers\LabelController@addNoteToLabel');
Route::post('/editLabelname', 'App\Http\Controllers\LabelController@updateLabel');
Route::get('/getlabels', 'App\Http\Controllers\LabelController@getLabels');
Route::post('/deleteLabel', 'App\Http\Controllers\LabelController@deleteLabel');
Route::post('/addnotetolabel', 'App\Http\Controllers\LabelController@addNoteToLabel');
Route::post('/deletenotefromlabel', 'App\Http\Controllers\LabelController@deleteNoteFromLabel');


