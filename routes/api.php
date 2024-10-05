<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {
    // Project CRUD routes
    Route::apiResource('projects', ProjectController::class);
    // Task CRUD routes
    Route::apiResource('projects.tasks', TaskController::class);
});
