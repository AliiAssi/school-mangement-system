<?php

use App\Http\Controllers\ClassController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {
    Route::resource('subjects', SubjectController::class);
    Route::resource('classes', ClassController::class);
    // Route::get('/teachers/{user}', [UserController::class, 'show'])->name('teachers.show');
    // Route::get('/teachers/{teacher}/edit', [UserController::class, 'edit'])->name('teachers.edit');
    // Route::put('/teachers/{teacher}', [UserController::class, 'update'])->name('teachers.update');
    Route::resource('teachers', UserController::class);
});
require __DIR__.'/auth.php';
