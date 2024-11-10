<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\CategoryController;

Route::get('/timeline', function () {
    return view('timeline');
})->name('timeline');

Route::get('/', function () {
    return view('pages.auth.login');
})->name('login');

Route::get('/register', function () {
    return view('pages.auth.register');
})->name('register');

Route::get('/change-password', function () {
    return view('pages.auth.change-password');
})->name('change-password');

Route::post('/register', [AuthController::class, 'register']);
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth')->post('/change-password', [AuthController::class, 'changePassword']);

Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline');
Route::get('/api/events', [TimelineController::class, 'getEvents']);
Route::delete('/api/events/{id}', [TimelineController::class, 'deleteEvent']);
Route::post('/api/events', [TimelineController::class, 'create']);
Route::get('/api/categories', [CategoryController::class, 'index']);
Route::post('/api/categories', [CategoryController::class, 'create']);
Route::delete('/api/categories/{id}', [CategoryController::class, 'destroy']);