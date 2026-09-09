<?php

use App\Http\Controllers\Api\SmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('sms')->middleware('sms.auth')->group(function () {
    Route::post('/send', [SmsController::class, 'send'])->name('sms.send');
    Route::post('/send-bulk', [SmsController::class, 'sendBulk'])->name('sms.send_bulk');
    Route::get('/status/{messageId}', [SmsController::class, 'status'])->name('sms.status');
});
