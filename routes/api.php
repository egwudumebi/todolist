<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/tasks', [TaskController::class, 'index']);
Route::get('{user_id}/tasks', [TaskController::class, 'getUserTasks']);
Route::prefix('/task')->group( function() {
    Route::post('/store', [TaskController::class, 'store']);
    Route::put('/update/{id}', [TaskController::class, 'update']);
    Route::delete('/delete/{id}', [TaskController::class, 'destroy']);
    Route::get('/{id}', [TaskController::class, 'show']);
});

Route::prefix('/user')->group( function() {
    Route::get('/register', [UserController::class, 'create']);
    Route::post('/register', [UserController::class, 'store']);
    Route::post('/login', [UserController::class, 'login']);
});
