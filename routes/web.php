<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicalCaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Requires Auth)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [MedicalCaseController::class, 'dashboard'])->name('dashboard');

    // Medical Cases Web Resource
    Route::get('/cases', [MedicalCaseController::class, 'index'])->name('cases.index');
    Route::get('/cases/create', [MedicalCaseController::class, 'create'])->name('cases.create');
    Route::post('/cases', [MedicalCaseController::class, 'store'])->name('cases.store');
    Route::get('/cases/{case}', [MedicalCaseController::class, 'show'])->name('cases.show');
    Route::get('/cases/{case}/edit', [MedicalCaseController::class, 'edit'])->name('cases.edit');
    Route::put('/cases/{case}', [MedicalCaseController::class, 'update'])->name('cases.update');
    Route::delete('/cases/{case}', [MedicalCaseController::class, 'destroy'])->name('cases.destroy');
});
