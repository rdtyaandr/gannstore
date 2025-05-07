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

    Route::resource('struks', StrukController::class);
    Route::post('/struks/preview', [StrukController::class, 'preview'])->name('struks.preview');
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
