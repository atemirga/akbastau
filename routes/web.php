<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\UserImportController;
use App\Http\Controllers\PDFGeneratorController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\NotificationController;


Route::get('/', function () {
    return view('welcome');
});


//Route::get('/notifications', [NotificationController::class, 'index'])->name('pages.notifications');

Route::get('/files/{fileName}', function ($fileName) {
    $filePath = storage_path("files/{$fileName}");

    if (!file_exists($filePath)) {
        abort(404, 'Файл не найден.');
    }

    return response()->file($filePath);
})->name('files.download');

Route::get('/upload-files', [FileUploadController::class, 'showUploadForm'])->name('upload.files.form');
Route::post('/upload-files', [FileUploadController::class, 'uploadFiles'])->name('upload.files');
Route::get('/generate-pdfs', [PDFGeneratorController::class, 'generatePDFs']);
Route::get('/import-users', [UserImportController::class, 'importForm'])->name('import.form');
Route::post('/import-users', [UserImportController::class, 'import'])->name('import');

Route::post('/login', [CustomLoginController::class, 'login'])->name('login');

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


    Route::get('/profile/edit', function () {
        return view('profile.edit');
    })->name('profile.edit');

    Route::put('/profile', [
        ProfileController::class, 'update'
    ])->name('profile.update');

    Route::put('/profile/password', [
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

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->name('show'); // Просмотр конкретного предложения
        Route::post('/store', [UserController::class, 'store'])->name('store'); // Сохранение нового предложения
        Route::put('/{user}', [UserController::class, 'update'])->name('update'); // Обновление предложения
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy'); // Удаление предложения
    });

    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index'); // Показ списка отделов
        Route::post('/store', [DepartmentController::class, 'store'])->name('store'); // Сохранение нового отдела
        Route::put('/{department}', [DepartmentController::class, 'update'])->name('update'); // Обновление отдела
        Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy'); // Удаление отдела
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index'); // Показ всех предложений
        Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/{notification}/update-status', [NotificationController::class, 'updateProposalStatus'])->name('updateProposalStatus');
                
    });
});
