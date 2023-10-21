<?php

use App\Http\Controllers\Api\ApiActivityController;
use App\Http\Controllers\Api\ApiAthleteController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiFeedController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [LoginController::class, 'authenticateApi']);
Route::post('/report/crash', [ApiController::class, 'errorReporting']);

Route::middleware('auth:sanctum')->get('/feed', [ApiFeedController::class, 'feed']);
Route::middleware('auth:sanctum')->get('/activity/{id}/comments', [ApiActivityController::class, 'activityComments']);
Route::middleware('auth:sanctum')->post('/upload', [UploadController::class, 'upload']);

/* Activity */
Route::group(['as' => 'api.', 'prefix' => 'activity', 'middleware' => 'auth:sanctum'], function () {
    Route::post('{id}/like', [ApiActivityController::class, 'like'])->name('like');
    Route::delete('{id}/like', [ApiActivityController::class, 'unlike'])->name('unlike');
});
/* Athlete */
Route::group(['as' => 'api.', 'prefix' => 'athlete', 'middleware' => 'auth:sanctum'], function () {
    Route::get('{id}', [ApiAthleteController::class, 'athlete'])->name('athlete');
    Route::post('{id}/subscribe', [ApiAthleteController::class, 'subscribe'])->name('subscribe');
    Route::delete('{id}/subscribe', [ApiAthleteController::class, 'unsubscribe'])->name('unsubscribe');
});
