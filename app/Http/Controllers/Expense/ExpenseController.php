<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\ExpenseRequest;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authenticatedUser = $request->user()->loadMissing('role');

        $expenses = [];
        $statement = Expense::with('category');

        if ($authenticatedUser->role->name !== 'Admin') {
            $expenses = $statement->where('user_id', $authenticatedUser->id);
        } else {
            $expenses = $statement;
        }

        return Inertia::render('Expenses', ['categories' => Category::all()->map(function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->name
            ];
        }), 'expenses' => $expenses->orderByDesc('created_at')->get()->map(function (Expense $expense) {
            return [
                'id' => $expense->id,
                'amount' => number_format($expense->amount, 2),
                'createdAt' => $expense->created_at,
                'category' => $expense->category->name
            ];
        })]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        $validated = $request->validated();

        Expense::create($validated);

        return back()->with('message', 'success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $validated = $request->validated();

        $expense->update($validated);

        return back()->with('message', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
    }
}
