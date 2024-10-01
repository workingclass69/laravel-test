<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('showLogin');
Route::post('/login', [AuthController::class, 'loginWeb'])->name('loginWeb');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('showRegister');
Route::post('/register', [AuthController::class, 'registerWeb'])->name('registerWeb');

Route::post('/logout', function () {
    Auth::logout();
    session()->forget('authToken');
    return redirect()->route('showLogin');
})->name('logout');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');


Route::middleware(['auth'])->group(function () {
    Route::post('/tasks/{taskId}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::put('/tasks/{taskId}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
});
