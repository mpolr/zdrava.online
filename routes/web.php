<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DownloadAppController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', ['as' => 'index', function () {
    if (Auth::user()) { // TODO: Сделать через middleware
        return redirect()->route('site.dashboard');
    }

    return view('index');
}]);

Route::get('download-app', [DownloadAppController::class, 'index'])->name('app.download');

Route::get('dashboard', ['as' => 'site.dashboard', function () {
    return view('dashboard');
}])->middleware([\App\Http\Middleware\Authenticate::class]);

/* Аутентификация */
Route::group(['prefix' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('auth.login');
    });

    Route::get('logout', [LoginController::class, 'logout'])->name('auth.logout');
    Route::get('login', ['as' => 'auth.login', function () {
        if (Auth::user()) { // TODO: Сделать через middleware
            return redirect()->route('site.dashboard');
        }

        return view('auth.login');
    }]);
    Route::get('register', ['as' => 'auth.register', function () {
        if (Auth::user()) { // TODO: Сделать через middleware
            return redirect()->route('site.dashboard');
        }

        return view('auth/register');
    }]);

    Route::post('login', [LoginController::class, 'authenticate'])->name('auth.login.post');
    Route::post('register', [RegisterController::class, 'register'])->name('auth.register.post');
});

/* Настройки */
Route::group(['prefix' => 'settings', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('settings.profile');
    });

    Route::get('profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::get('account', [SettingsController::class, 'account'])->name('settings.account');
    Route::get('privacy', [SettingsController::class, 'privacy'])->name('settings.privacy');
});

/* Настройки */
Route::group(['prefix' => 'upload'], function () {
    Route::get('/', function () {
        return redirect()->route('site.dashboard');
    });

    Route::post('avatar', [UploadController::class, 'avatar'])->name('upload.avatar');
    Route::post('workout', [UploadController::class, 'workout'])->name('upload.workout');
});
