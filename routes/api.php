<?php

use App\Http\Controllers\Api\ApiActivityController;
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

Route::middleware('auth:sanctum')->get('/feed', [ApiFeedController::class, 'feed']);
Route::middleware('auth:sanctum')->get('/activity/{id}/comments', [ApiActivityController::class, 'activityComments']);
Route::middleware('auth:sanctum')->post('/upload', [UploadController::class, 'workoutApi']);

Route::group(['as' => 'api.', 'prefix' => 'activity', 'middleware' => 'auth:sanctum'], function () {
    Route::post('{id}/like', [ApiActivityController::class, 'like'])->name('like');
    Route::delete('{id}/like', [ApiActivityController::class, 'unlike'])->name('unlike');
});
