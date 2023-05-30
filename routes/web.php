<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadAppController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UploadController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', ['as' => 'index', function () {
    if (Auth::user()) { // TODO: Сделать через middleware
        return redirect()->route('site.dashboard');
    }

    return view('index');
}]);

Route::get('download-app', [DownloadAppController::class, 'index'])->name('app');
Route::get('download-app/download/{version?}', [DownloadAppController::class, 'download'])->name('app.download');

Route::middleware(Authenticate::class)->get('dashboard', [DashboardController::class, 'index'])->name('site.dashboard');
Route::middleware(Authenticate::class)->get('dashboard', [DashboardController::class, 'index'])->name('site.dashboard');

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

/* Спортсмен */
Route::group(['prefix' => 'athlete', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
//        return redirect()->route('settings.profile');
    });

    Route::get('training', [AthleteController::class, 'training'])->name('athlete.training');
});

/* Тренировки */
Route::group(['prefix' => 'activities', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('athlete.training');
    });

    Route::get('/{id}', [ActivitiesController::class, 'get'])->name('activities.get');
});

/* Настройки */
Route::group(['prefix' => 'settings', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('settings.profile');
    });

    Route::get('profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::get('account', [SettingsController::class, 'account'])->name('settings.account');

    Route::post('lang', [SettingsController::class, 'setLocale'])->name('settings.account.set.locale');

    Route::get('privacy', [SettingsController::class, 'privacy'])->name('settings.privacy');
});

/* Загрузка файлов на сервер */
Route::group(['prefix' => 'upload'], function () {
    Route::get('/', function () {
        return redirect()->route('site.dashboard');
    });

    Route::get('workout', function () {
        return view('upload/workout');
    });

    Route::post('workout', [UploadController::class, 'workout'])->name('upload.workout');
});

/* Друзья */
Route::group(['prefix' => 'friends', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('friends.find');
    });

    Route::any('find', [FriendsController::class, 'find'])->name('friends.find');
});
