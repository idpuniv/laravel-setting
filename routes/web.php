<?php

use Illuminate\Support\Facades\Route;
use Idpuniv\Http\Controllers\SettingController;

Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
});