<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FeedController;
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

Route::middleware('auth:sanctum')->get('/feed', [FeedController::class, 'feed']);
Route::middleware('auth:sanctum')->post('/upload', [\App\Http\Controllers\UploadController::class, 'workoutApi']);
