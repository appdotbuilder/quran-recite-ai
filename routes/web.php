<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', [App\Http\Controllers\QuranController::class, 'index'])->name('home');
Route::get('/surah/{surah}', [App\Http\Controllers\QuranController::class, 'show'])->name('surah.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    
    Route::resource('recitation', App\Http\Controllers\RecitationController::class);
    Route::post('/api/recitation', [App\Http\Controllers\RecitationController::class, 'store'])
        ->middleware(App\Http\Middleware\AuthenticateApi::class)
        ->name('api.recitation.store');
});

Route::get('/api/qaris', [App\Http\Controllers\QariController::class, 'index'])->name('qaris');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
