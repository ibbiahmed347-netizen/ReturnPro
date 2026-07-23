<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\IncomeTaxReturnController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\SalesTaxReturnController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::resource('clients', ClientController::class);
    Route::resource('vouchers', VoucherController::class)->except(['edit', 'update']);
    Route::post('vouchers/{voucher}/receipts', [ReceiptController::class, 'store'])->name('receipts.store');
    Route::resource('income-tax', IncomeTaxReturnController::class);
    Route::post('income-tax/{incomeTax}/publish', [IncomeTaxReturnController::class, 'publish'])->name('income-tax.publish');
    Route::resource('sales-tax', SalesTaxReturnController::class);
    Route::post('sales-tax/{salesTax}/publish', [SalesTaxReturnController::class, 'publish'])->name('sales-tax.publish');
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::resource('expenses', ExpenseController::class)->except(['show']);
    Route::resource('notices', NoticeController::class);

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/collections', [ReportController::class, 'collections'])->name('reports.collections');
    Route::get('reports/outstanding', [ReportController::class, 'outstanding'])->name('reports.outstanding');
    Route::get('reports/expenses', [ReportController::class, 'expenses'])->name('reports.expenses');
    Route::get('reports/client-wise', [ReportController::class, 'clientWise'])->name('reports.client-wise');
    Route::get('reports/income-tax', [ReportController::class, 'incomeTax'])->name('reports.income-tax');
    Route::get('reports/sales-tax', [ReportController::class, 'salesTax'])->name('reports.sales-tax');
});