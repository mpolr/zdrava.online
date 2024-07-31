<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\Auth\ForgotResetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DownloadAppController;
use App\Http\Controllers\Import\StravaController;
use App\Http\Controllers\UploadController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

Route::get('/', ['as' => 'index', function () {
    if (Auth::user()) { // TODO: Сделать через middleware
        return redirect()->route('site.dashboard');
    }

    return view('index');
}]);

/* Юридическая информация */
Route::group(['prefix' => 'legal'], function () {
    Route::get('privacy', function () {
        return view('legal.privacy');
    })->name('legal.privacy');
    Route::get('terms', function () {
        return view('legal.terms');
    })->name('legal.terms');
    Route::get('account-deletion', function () {
        return view('legal.account-deletion');
    })->name('legal.account-deletion');
});

Route::get('mobile', [DownloadAppController::class, 'mobile'])->name('mobile');

Route::middleware(Authenticate::class)->get('dashboard', \App\Http\Livewire\Dashboard::class)->name('site.dashboard');

/* Аутентификация */
Route::group(['prefix' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('auth.login');
    });

    Route::get('logout', [LoginController::class, 'logout'])->name('auth.logout');

    Route::get('login', \App\Http\Livewire\Auth\Login::class)->name('auth.login');
    Route::get('register', \App\Http\Livewire\Auth\Register::class)->name('auth.register');

    Route::get('verify-email/{token?}', [RegisterController::class, 'verifyEmail'])->name('auth.verify.email');

    Route::get('forgot-password', [ForgotResetController::class, 'forgot'])->name('auth.password.request');
    Route::post('forgot-password', [ForgotResetController::class, 'sendLink'])->middleware('guest')->name('auth.password.email');

    Route::get('reset-password/{token}', [ForgotResetController::class, 'showResetPassword'])->middleware('guest')->name('password.reset');
    Route::post('reset-password', [ForgotResetController::class, 'resetPassword'])->middleware('guest')->name('auth.password.reset');
});

/* Спортсмен */
Route::group(['prefix' => 'athlete', 'middleware' => 'auth'], function () {
    Route::get('training', [AthleteController::class, 'training'])->name('athlete.training');
    // Подписчики пользователя
    Route::get('subscribers', [AthleteController::class, 'subscribers'])->name('athlete.subscribers');
    Route::get('{id}/subscribers', [AthleteController::class, 'subscribers'])->name('athlete.subscribers.user');
    // Подписки пользователя (на кого подписан)
    Route::get('subscriptions', [AthleteController::class, 'subscriptions'])->name('athlete.subscriptions');
    Route::get('{id}/subscriptions', [AthleteController::class, 'subscriptions'])->name('athlete.subscriptions.user');
    Route::get('{id?}', \App\Http\Livewire\Athlete\Profile::class)->name('athlete.profile');
});

/* Тренировки */
Route::group(['prefix' => 'activities', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('athlete.training');
    });

    Route::get('/{id}', App\Http\Livewire\Activity\Show::class)->name('activities.get');
    Route::post('/{id}/delete', [App\Http\Livewire\Activity\Show::class, 'delete'])->name('activities.delete');
    Route::get('/{id}/edit', App\Http\Livewire\Activity\Edit::class)->name('activities.edit');
});

/* Настройки */
Route::group(['prefix' => 'settings', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('settings.profile');
    });

    Route::get('profile', \App\Http\Livewire\Settings\Profile::class)->name('settings.profile');
    Route::get('account', \App\Http\Livewire\Settings\Account::class)->name('settings.account');
    Route::get('account/delete', \App\Http\Livewire\Settings\Account\Delete::class)->name('settings.account.delete');
    Route::get('privacy', \App\Http\Livewire\Settings\Privacy::class)->name('settings.privacy');
});

/* Загрузка файлов на сервер */
Route::group(['prefix' => 'upload', 'middleware' => 'auth'], function () {
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

    Route::get('find', \App\Http\Livewire\Search\Users::class)->name('friends.find');
    Route::get('requests', \App\Http\Livewire\Friends\Requests::class)->name('friends.requests');
});

/* Сегменты */
Route::group(['prefix' => 'segments'], function () {
    Route::get('/', function () {
        return redirect()->route('site.dashboard');
    });

    Route::get('explore/{id?}', \App\Http\Livewire\Segments\Explore::class)->name('segments.explore');
});

/* Админка */
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', \App\Http\Livewire\Admin\Index::class)->name('admin.index');
    Route::get('users', \App\Http\Livewire\Admin\Users::class)->name('admin.users');
    Route::get('segments', \App\Http\Livewire\Admin\Segments::class)->name('admin.segments');
    Route::get('crashlogs/{issueId?}', \App\Http\Livewire\Admin\CrashLogs::class)->name('admin.crashlogs');
    Route::get('import/strava', \App\Http\Livewire\Admin\Import\Strava::class)->name('admin.import.strava.csv');
});

/* Импорт данных */
Route::group(['prefix' => 'import'], function () {
    /* Strava */
    Route::get('strava/auth', [StravaController::class, 'auth'])->name('strava.auth')->middleware('auth');
    Route::get('strava/token', [StravaController::class, 'getToken'])->name('strava.token');
});
