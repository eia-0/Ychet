<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TemplateFieldController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Профиль
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Шаблон полей
    Route::resource('template-fields', TemplateFieldController::class);

    // Клиенты
    Route::resource('clients', ClientController::class)
        ->only(['create', 'store', 'show', 'destroy']);

    // Сеансы
    Route::get('clients/{client}/sessions/create', [ClientSessionController::class, 'create'])
        ->name('clients.sessions.create');
    Route::post('clients/{client}/sessions', [ClientSessionController::class, 'store'])
        ->name('clients.sessions.store');
    Route::delete('clients/{client}/sessions/{session}', [ClientSessionController::class, 'destroy'])
        ->name('clients.sessions.destroy');
});

require __DIR__.'/auth.php';