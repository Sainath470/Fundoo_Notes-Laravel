<?php

use App\Http\Controllers\JwtAuthController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\NoteController;
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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [JwtAuthController::class, 'login']);
    Route::post('register', [JwtAuthController::class, 'register']);
    Route::post('signout', [JwtAuthController::class, 'signout']);
    Route::post('forgotPassword', [JwtAuthController::class, 'forgotPassword']);
    Route::post('resetPassword', [JwtAuthController::class, 'resetPassword']);


/**
 * routes for Note controller
 */
    Route::post('addNotes',[NoteController::class, 'createNote']);
    Route::get('getNotes', [NoteController::class, 'getNotes']);
    Route::post('updateNote',[NoteController::class, 'updateNote']);
    Route::post('deleteNote', [NoteController::class, 'deleteNote']);

/**
 * routes for Label controller
 */
    Route::post('makeLabel', [LabelController::class, 'createLabel']);
    Route::post('noteToLabel', [LabelController::class, 'addNoteToLabel']);
    Route::post('editLabelname', [LabelController::class, 'updateLabel']);
    Route::post('deleteLabel', [LabelController::class, 'deleteLabel']);
    Route::post('getLabels', [LabelController::class, 'getLabels']);
    Route::post('addnotetolabel', [LabelController::class, ' addNoteToLabel']);
    Route::post('deletenotefromlabel', [LabelController::class,'deleteNoteFromLabel']);
    Route::get('getAllNotesLabels', [LabelController::class,'getAllNotesInLabels']);
});



