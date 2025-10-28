<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthMonitorController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RejectedController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\WebAuthController;

Route::get('/', fn() => redirect('/login'));

Route::get('/login', [WebAuthController::class, 'loginPage'])->name('login');
Route::post('/login', [WebAuthController::class, 'loginWeb'])->name('login.post');

Route::get('/dashboard', [WebAuthController::class, 'dashboard'])->name('dashboard');

// SETTINGS & USER MANAGEMENT
Route::get('/settings', [WebAuthController::class, 'settingsPage'])->name('settings');
Route::post('/settings/add-user', [WebAuthController::class, 'addUserWeb'])->name('settings.addUser');
Route::post('/settings/change-password/{id}', [WebAuthController::class, 'changePasswordWeb'])->name('settings.changePassword');
Route::delete('/settings/delete-user/{id}', [WebAuthController::class, 'deleteUserWeb'])->name('settings.deleteUser');

// LOGOUT
Route::post('/logout', [WebAuthController::class, 'logoutWeb'])->name('logout');

// Route::get('/', [AuthMonitorController::class, 'showLoginForm']);
// Route::get('/loginmdw', [AuthMonitorController::class, 'showLoginForm'])->name('login');
Route::post('/loginmdw', [AuthMonitorController::class, 'loginmdw']);

// Route::post('/logout', function () {
//     Session::flush();
//     return redirect('/loginmdw')->with('success', 'Berhasil logout.');
// })->name('logout');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
Route::get('/billing/export', [BillingController::class, 'exportExcel'])->name('billing.export');

Route::get('/rejected', [RejectedController::class, 'index'])->name('rejected.index');

Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
Route::get('/stock/export', [StockController::class, 'exportExcel'])->name('stock.export');


