<?php

use App\Http\Controllers\EnumController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::get('/enums', [EnumController::class, 'getEnums']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->middleware('role:manager');

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->middleware('auth');
        Route::post('/create', [TaskController::class, 'store'])->middleware('role:manager');
        Route::put('update/{id}', [TaskController::class, 'update'])->middleware('auth');
    
        // Public Routes for tasks (like viewing task details) that any user can access
        Route::get('tasks/{id}', [TaskController::class, 'show']);
    });
});
