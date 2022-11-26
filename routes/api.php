<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\IndexController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Signup routes
Route::post('signup', [AuthController::class, 'signup']);
Route::post('signin', [AuthController::class, 'signin']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('signout', [AuthController::class, 'signout']);
    Route::get('welcome', [IndexController::class, 'greetings']);
});
