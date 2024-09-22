<?php

use App\Http\Controllers\Api\ApiActivityController;
use App\Http\Controllers\Api\ApiAthleteController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiFeedController;
use App\Http\Controllers\Api\ApiSearchController;
use App\Http\Controllers\Api\ApiUpdate;
use App\Http\Controllers\Api\ApiUpload;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:6,1')->post('/login', [LoginController::class, 'authenticateApi']);
Route::middleware('throttle:6,1')->post('/register', [RegisterController::class, 'register']);
Route::middleware('throttle:6,1')->post('/report/crash', [ApiController::class, 'errorReporting']);

Route::middleware('auth:sanctum')->get('/feed', [ApiFeedController::class, 'feed']);
Route::middleware('auth:sanctum')->post('/upload', [UploadController::class, 'workout', ['fromApp' => true]]);

/* Activity */
Route::group(['as' => 'api.', 'prefix' => 'activity', 'middleware' => 'auth:sanctum'], static function () {
    Route::get('{id}', [ApiActivityController::class, 'get'])->name('get');
    Route::post('{id}/like', [ApiActivityController::class, 'like'])->name('like');
    Route::delete('{id}/like', [ApiActivityController::class, 'unlike'])->name('unlike');
    Route::post('{id}/comment', [ApiActivityController::class, 'addComment'])->name('addComment');
    Route::get('{id}/comments', [ApiActivityController::class, 'activityComments'])->name('activityComments');
    Route::get('{id}/likes', [ApiActivityController::class, 'activityLikes'])->name('activityLikes');
});
/* Athlete */
Route::group(['as' => 'api.', 'prefix' => 'athlete', 'middleware' => 'auth:sanctum'], static function () {
    Route::get('{id}', [ApiAthleteController::class, 'athlete'])->name('athlete');
    Route::post('{id}/subscribe', [ApiAthleteController::class, 'subscribe'])->name('subscribe');
    Route::delete('{id}/subscribe', [ApiAthleteController::class, 'unsubscribe'])->name('unsubscribe');
});
/* Search */
Route::group(['as' => 'api.', 'prefix' => 'search', 'middleware' => 'auth:sanctum'], static function () {
    Route::post('/athletes', [ApiSearchController::class, 'athletes'])->name('athletes');
});
/* Upload */
Route::group(['as' => 'api.', 'prefix' => 'upload', 'middleware' => 'auth:sanctum'], static function () {
    Route::post('avatar', [ApiUpload::class, 'avatar'])->name('avatar');
});
/* Update */
Route::group(['as' => 'api.', 'prefix' => 'update'], static function () {
    Route::post('check', [ApiUpdate::class, 'check'])->name('updateCheck');
});
