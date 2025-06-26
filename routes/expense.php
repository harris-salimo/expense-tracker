<?php

use App\Http\Controllers\Expense\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('expenses/{expense}/edit', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/{expense}/remove', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
});
