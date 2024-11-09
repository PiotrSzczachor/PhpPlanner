<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/timeline', function () {
    return view('timeline');
})->name('timeline');

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Handle registration
Route::post('/register', [AuthController::class, 'register']);

// Handle login
Route::post('/', [AuthController::class, 'login']);

use App\Http\Controllers\TimelineController;

Route::get('/timeline', [TimelineController::class, 'index'])->name('timeline');
Route::get('/api/events', [TimelineController::class, 'getEvents']); // API endpoint to fetch events
Route::delete('/api/events/{id}', [TimelineController::class, 'deleteEvent']); // API endpoint to delete an event
Route::post('/api/events', [TimelineController::class, 'create']);
