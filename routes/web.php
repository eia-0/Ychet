<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
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
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Шаблоны
    Route::resource('templates', TemplateController::class);
    Route::post('templates/{template}/fields', [TemplateController::class, 'addField'])->name('templates.fields.store');
    Route::delete('templates/{template}/fields/{field}', [TemplateController::class, 'removeField'])->name('templates.fields.destroy');
    Route::get('templates/{template}/fields-data', [TemplateController::class, 'getFields'])->name('templates.fields.data');

    // Клиенты
    Route::resource('clients', ClientController::class)->only(['create', 'store', 'show', 'destroy']);

    // Сеансы
    Route::get('clients/{client}/sessions/create', [ClientSessionController::class, 'create'])->name('clients.sessions.create');
    Route::post('clients/{client}/sessions', [ClientSessionController::class, 'store'])->name('clients.sessions.store');
    Route::get('clients/{client}/sessions/{session}/edit', [ClientSessionController::class, 'edit'])->name('clients.sessions.edit');
    Route::patch('clients/{client}/sessions/{session}', [ClientSessionController::class, 'update'])->name('clients.sessions.update');
    Route::delete('clients/{client}/sessions/{session}', [ClientSessionController::class, 'destroy'])->name('clients.sessions.destroy');

    // Админка
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/users/{user}', [AdminController::class, 'show'])->name('users.show');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');
    });
});

require __DIR__.'/auth.php';