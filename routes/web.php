<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SmsLogController;
use App\Http\Controllers\WebSmsController;
use Illuminate\Support\Facades\Route;

// Authentification
Route::get('/login', [LoginController::class, 'showForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Redirection racine
Route::get('/', fn () => redirect()->route('dashboard'));

// Dashboard et fonctionnalités (authentifiés)
Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Envoi SMS depuis l'interface web (pas de Bearer Token nécessaire)
    Route::post('/web/sms/send', [WebSmsController::class, 'send'])->name('web.sms.send');
    Route::post('/web/sms/send-bulk', [WebSmsController::class, 'sendBulk'])->name('web.sms.send_bulk');
    Route::get('/web/sms/status/{messageId}', [WebSmsController::class, 'status'])->name('web.sms.status');

    // Gestion des tokens Bearer
    Route::get('/tokens', [ApiTokenController::class, 'index'])->name('tokens.index');
    Route::post('/tokens', [ApiTokenController::class, 'store'])->name('tokens.store');
    Route::patch('/tokens/{apiToken}/revoke', [ApiTokenController::class, 'revoke'])->name('tokens.revoke');
    Route::delete('/tokens/{apiToken}', [ApiTokenController::class, 'destroy'])->name('tokens.destroy');

    // Journal des SMS & Logs
    Route::get('/logs', [SmsLogController::class, 'index'])->name('logs.index');
});
