<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthMonitorController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RejectedController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\FormStockController;
use App\Http\Controllers\ArcItmMastController;
use App\Http\Controllers\MarginController;
use App\Http\Controllers\ARCItemPriceItalyController;
use App\Http\Controllers\StockManagementController;

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

// Stock Management Routes
Route::get('/stock-management', [StockManagementController::class, 'index'])->name('stock-management.index');
Route::post('/stock-management/kalkulasi', [StockManagementController::class, 'kalkulasi'])->name('stock-management.kalkulasi');
Route::match(['get', 'post'], '/stock-management/download-json', [StockManagementController::class, 'downloadJson'])->name('stock-management.download-json');
Route::post('/stock-management/download-json-by-material-doc', [StockManagementController::class, 'downloadJsonByMaterialDocument'])->name('stock-management.download-json-by-material-doc');

Route::get('import', [ImportController::class, 'showForm'])->name('showForm');
Route::post('import', [ImportController::class, 'import'])->name('import');
Route::post('save-manual', [FormStockController::class, 'store'])->name('save_manual');

Route::get('download-json', [ImportController::class, 'downloadJson']);

Route::resource('arc-itm-mast', ArcItmMastController::class);
Route::resource('margin', MarginController::class);

Route::get('arc-item-price-italy/create', [ARCItemPriceItalyController::class, 'createPage'])->name('arc-item-price-italy.create');
Route::post('arc-item-price-italy', [ARCItemPriceItalyController::class, 'store'])->name('arc-item-price-italy.store');

// Manage price routes
Route::get('arc-item-price-italy/manage/{arcimCode}', [ARCItemPriceItalyController::class, 'managePrice'])->name('arc-item-price-italy.manage');
Route::post('arc-item-price-italy/manage/{arcimCode}', [ARCItemPriceItalyController::class, 'storeFromManage'])->name('arc-item-price-italy.store-manage');
Route::put('arc-item-price-italy/manage/{arcimCode}/{id}', [ARCItemPriceItalyController::class, 'updateFromManage'])->name('arc-item-price-italy.update-manage');

Route::prefix('api')->group(function () {
    Route::get('arc-item-price-italy', [ARCItemPriceItalyController::class, 'index']);
    Route::post('arc-item-price-italy', [ARCItemPriceItalyController::class, 'store']);
    Route::get('arc-item-price-italy/{id}', [ARCItemPriceItalyController::class, 'show']);
    Route::put('arc-item-price-italy/{id}', [ARCItemPriceItalyController::class, 'update']);
});

