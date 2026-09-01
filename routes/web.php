<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Landing Page / Institucional
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| Rotas do Painel do Cliente (Tenants)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // Renderiza a página Dashboard dentro da pasta App
        return Inertia::render('App/Dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Rotas de Gerenciamento do SaaS (Super Admins)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', function () {
        // Renderiza o Dashboard dos Super Admins
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
