<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProjectController;

// Redirect to login
Route::get('/', function () {
    return redirect()->to('/login');
})->name('login');

// Login routes
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout routes
Route::post('/logout', [LoginController::class, 'logout']);

// Panel routes
Route::middleware('auth:web')->group(function () {

    // Dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Project details route
    Route::get('projects/{id}', [ProjectController::class, 'show'])->name('projects.show');

});
