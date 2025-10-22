<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RejectedController;
use App\Http\Controllers\StockController;

Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/manual-login', [AuthController::class, 'showManualLogin'])->name('manual.login');
Route::post('/manual-login', [AuthController::class, 'manualLogin']);

Route::post('/logout', function () {
    Session::flush();
    return redirect('/login')->with('success', 'Berhasil logout.');
})->name('logout');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
Route::get('/billing/export', [BillingController::class, 'exportExcel'])->name('billing.export');

Route::get('/rejected', [RejectedController::class, 'index'])->name('rejected.index');

Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
Route::get('/stock/export', [StockController::class, 'exportExcel'])->name('stock.export');


