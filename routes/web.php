<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TemplateFieldController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Главная страница (Welcome)
Route::get('/', function () {
    return view('welcome');
});

// Дашборд (после входа)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Все маршруты для авторизованных пользователей
Route::middleware('auth')->group(function () {

    // ========== Профиль (имя, email, часовой пояс) ==========
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ========== Настройка полей шаблона ==========
    Route::resource('template-fields', TemplateFieldController::class);

    // ========== Клиенты ==========
    Route::resource('clients', ClientController::class)
        ->only(['create', 'store', 'show', 'destroy']);

    // ========== Сеансы клиентов ==========
    Route::get('clients/{client}/sessions/create', [ClientSessionController::class, 'create'])
        ->name('clients.sessions.create');
    Route::post('clients/{client}/sessions', [ClientSessionController::class, 'store'])
        ->name('clients.sessions.store');
    Route::delete('clients/{client}/sessions/{session}', [ClientSessionController::class, 'destroy'])
        ->name('clients.sessions.destroy');

    // ========== Админ-панель (только для пользователей с ролью admin) ==========
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/users/{user}', [AdminController::class, 'show'])->name('users.show');   // <-- добавить
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
});
});

// Подключаем стандартные маршруты аутентификации Breeze
require __DIR__.'/auth.php';