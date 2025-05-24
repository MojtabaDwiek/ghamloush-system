<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Employees
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);
    
    // Safebox
    Route::prefix('safebox')->group(function () {
        Route::get('/', [App\Http\Controllers\SafeboxController::class, 'index'])->name('safebox.index');
        Route::get('/create', [App\Http\Controllers\SafeboxController::class, 'create'])->name('safebox.create');
        Route::post('/', [App\Http\Controllers\SafeboxController::class, 'store'])->name('safebox.store');
        Route::get('/{safebox}', [App\Http\Controllers\SafeboxController::class, 'show'])->name('safebox.show');
        
        // Transactions
        Route::get('/{safebox}/deposit', [App\Http\Controllers\TransactionController::class, 'createDeposit'])->name('safebox.deposit.create');
        Route::post('/{safebox}/deposit', [App\Http\Controllers\TransactionController::class, 'storeDeposit'])->name('safebox.deposit.store');
        Route::get('/{safebox}/withdraw', [App\Http\Controllers\TransactionController::class, 'createWithdrawal'])->name('safebox.withdraw.create');
        Route::post('/{safebox}/withdraw', [App\Http\Controllers\TransactionController::class, 'storeWithdrawal'])->name('safebox.withdraw.store');
        Route::get('/{safebox}/transfer', [App\Http\Controllers\TransactionController::class, 'createTransfer'])->name('safebox.transfer.create');
        Route::post('/{safebox}/transfer', [App\Http\Controllers\TransactionController::class, 'storeTransfer'])->name('safebox.transfer.store');
    });
    
    // Work Orders
    Route::prefix('work-orders')->group(function () {
        Route::get('/', [App\Http\Controllers\WorkOrderController::class, 'index'])->name('work-orders.index');
        Route::get('/create', [App\Http\Controllers\WorkOrderController::class, 'create'])->name('work-orders.create');
        Route::post('/', [App\Http\Controllers\WorkOrderController::class, 'store'])->name('work-orders.store');
        Route::get('/{workOrder}', [App\Http\Controllers\WorkOrderController::class, 'show'])->name('work-orders.show');
        Route::get('/{workOrder}/edit', [App\Http\Controllers\WorkOrderController::class, 'edit'])->name('work-orders.edit');
        Route::put('/{workOrder}', [App\Http\Controllers\WorkOrderController::class, 'update'])->name('work-orders.update');
        Route::post('/{workOrder}/complete', [App\Http\Controllers\WorkOrderController::class, 'complete'])->name('work-orders.complete');
        
        // Work order types
        Route::get('/type/{type}', [App\Http\Controllers\WorkOrderController::class, 'byType'])->name('work-orders.by-type');
    });
});