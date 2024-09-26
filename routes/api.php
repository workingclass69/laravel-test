<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [AuthController::class, 'users']);

    Route::get('/tasks', [TaskController::class, 'getAllTasks']);
    Route::get('/tasks/{taskId}', [TaskController::class, 'getTaskById']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{taskId}', [TaskController::class, 'update']);
    Route::delete('/tasks/{taskId}', [TaskController::class, 'delete']);
    Route::post('/tasks/{taskId}/update-status', [TaskController::class, 'updateStatus']);

    Route::get('/categories', [CategoryController::class, 'getAllCategories']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::delete('/categories/{categoryId}', [CategoryController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
});