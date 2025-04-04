<?php

use App\Http\Controllers\TSBMGameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register-and-save', [TSBMGameController::class, 'registerAndSave']);
Route::post('/login-and-save', [TSBMGameController::class, 'loginAndSave']);