<?php

use App\Http\Controllers\Expense\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expense.index');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expense.store');
    Route::put('expenses/{expense}/edit', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('expenses/{expense}/remove', [ExpenseController::class, 'destroy'])->name('expense.destroy');
});
