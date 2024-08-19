<?php

use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HandleAutonomousFeaturesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TimeTableController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {
    Route::resource('subjects', SubjectController::class);
    Route::resource('classes', ClassController::class);
    Route::resource('teachers', UserController::class);
    Route::resource('timetables', TimeTableController::class);
    Route::resource('settings', SettingsController::class);
    Route::get('/automatically',[HandleAutonomousFeaturesController::class,'handleTimeTableGeneration'])->name('automatically');
    Route::get('/export',[HandleAutonomousFeaturesController::class,'exportTimetablesToPdf'])->name('export');
});
require __DIR__.'/auth.php';
