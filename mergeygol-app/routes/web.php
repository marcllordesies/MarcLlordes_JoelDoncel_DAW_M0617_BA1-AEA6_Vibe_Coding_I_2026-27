<?php

use App\Http\Controllers\Admin\MatchEventController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PredictionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/matches/{match}', [MatchController::class, 'show'])->name('matches.show');

Route::middleware('auth')->group(function (): void {
    Route::post('/matches/{match}/predict', [PredictionController::class, 'store'])->name('predictions.store');
    Route::post('/matches/{match}/comments', [CommentController::class, 'store'])->name('comments.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::post('/matches/{match}/events', [MatchEventController::class, 'store'])->name('matches.events.store');
});
