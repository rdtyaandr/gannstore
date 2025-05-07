<?php

use App\Http\Controllers\StrukController;
use App\Http\Controllers\StrukFieldController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [StrukController::class, 'index'])->name('dashboard');

    // Rute Cuan (Keuntungan)
    Route::get('/cuan', [App\Http\Controllers\CuanController::class, 'index'])->name('cuan.index');
    Route::post('/cuan', [App\Http\Controllers\CuanController::class, 'store'])->name('cuan.store');
    Route::put('/cuan/update', [App\Http\Controllers\CuanController::class, 'update'])->name('cuan.update');
    Route::get('/cuan/check/{strukId}', [App\Http\Controllers\CuanController::class, 'checkCuanExists'])->name('cuan.check');

    // Route untuk form input manual
    Route::post('/struks', [StrukController::class, 'store'])->name('struks.store');

    // Route untuk preview dan save hasil scan
    Route::post('/struks/preview', [StrukController::class, 'preview'])->name('struks.preview');
    Route::post('/struks/from-scan', [StrukController::class, 'storeFromScan'])->name('struks.storeFromScan');

    // Other routes
    Route::post('/log-ocr', [StrukController::class, 'logOCR'])->name('struks.log-ocr');
    Route::resource('struks', StrukController::class)->except(['store']);
    Route::get('/struks/{struk}/financial', [StrukController::class, 'financial'])->name('struks.financial');
    Route::resource('struk-fields', StrukFieldController::class);

    // Route untuk mengambil field-field
    Route::get('/struk-fields', function () {
        return App\Models\StrukField::orderBy('order')->get();
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
