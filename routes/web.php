<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\Auth\CustomLoginController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomLoginController::class, 'login']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {


    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');
    Route::get('/map', function () {
        return view('pages.map');
    })->name('map');
    Route::get('/logs', function () {
        return view('pages.logs');
    })->name('logs');
    Route::get('/notifications', function () {
        return view('pages.notifications');
    })->name('notifications');
    Route::get('/users', function () {
        return view('pages.users');
    })->name('users');

    Route::get('/profile/edit', function () {
        return view('profile.edit');
    })->name('profile.edit');

    Route::put('/profile', [
        ProfileController::class, 'update'
    ])->name('profile.update');

    Route::post('/profile/password', [
        ProfileController::class, 'updatePassword'
    ])->name('profile.password');

    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [ProposalController::class, 'index'])->name('index'); // Показ всех предложений
        Route::get('/{proposal}', [ProposalController::class, 'show'])->name('show'); // Просмотр конкретного предложения
        Route::post('/{proposal}/update-status', [ProposalController::class, 'updateStatus'])->name('tickets.updateStatus');
        Route::post('/store', [ProposalController::class, 'store'])->name('store'); // Сохранение нового предложения
        Route::put('/{proposal}', [ProposalController::class, 'update'])->name('update'); // Обновление предложения
        Route::delete('/{proposal}', [ProposalController::class, 'destroy'])->name('destroy'); // Удаление предложения
    });

});
