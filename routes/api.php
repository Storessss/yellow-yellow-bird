<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TSBMLevelController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('/register', [UserController::class, 'register']);
// Route::post('/login', [UserController::class, 'login']);
// Route::post('/delete-account', [UserController::class, 'deleteAccount']);
// Route::middleware('auth:sanctum')->post('/save-tsbm-level', [TSBMLevelController::class, 'saveLevel']);
Route::post('/save-tsbm-level', [TSBMLevelController::class, 'saveLevel']);
Route::get('/get-random-tsbm-level', [TSBMLevelController::class, 'getRandomLevel']);
Route::get('/get-tsbm-level-by-code/{code}', [TSBMLevelController::class, 'getLevelByCode']);