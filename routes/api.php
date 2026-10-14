<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MedicalCaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Laravel Sanctum)
|--------------------------------------------------------------------------
*/

// Public authentication endpoint
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Protected Sanctum API endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // Case resource endpoints
    Route::get('/cases', [MedicalCaseController::class, 'index'])->name('api.cases.index');
    Route::post('/cases', [MedicalCaseController::class, 'store'])->name('api.cases.store');
    Route::get('/cases/{id}', [MedicalCaseController::class, 'show'])->name('api.cases.show');
    Route::put('/cases/{id}', [MedicalCaseController::class, 'update'])->name('api.cases.update');
    Route::delete('/cases/{id}', [MedicalCaseController::class, 'destroy'])->name('api.cases.destroy');
});
