<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
    Route::post('change-password', 'changePassword');
    Route::post('users', 'addUser');
    Route::delete('users/{id}', 'deleteUser');
});
