<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\ExpenseRequest;
use App\Models\Category;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authenticatedUser = $request->user()->loadMissing('role');

        $expensesQuery = Expense::with('category');

        if ($authenticatedUser->role->name !== 'Admin') {
            $expensesQuery->where('user_id', $authenticatedUser->id);
        }

        $filterPeriod = $request->query('period');

        switch ($filterPeriod) {
            case 'past_week':
                $expensesQuery->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay());
                $expensesQuery->where('created_at', '<=', Carbon::now()->endOfDay());
                break;
            case 'past_month':
                $expensesQuery->where('created_at', '>=', Carbon::now()->subMonth()->startOfDay());
                $expensesQuery->where('created_at', '<=', Carbon::now()->endOfDay());
                break;
            case 'past_three_months':
                $expensesQuery->where('created_at', '>=', Carbon::now()->subMonths(3)->startOfDay());
                $expensesQuery->where('created_at', '<=', Carbon::now()->endOfDay());
                break;
        }

        return Inertia::render('Expenses', ['categories' => Category::all()->map(function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        }), 'expenses' => $expensesQuery->orderByDesc('created_at')->get()->map(function (Expense $expense) {
            return [
                'id' => $expense->id,
                // 'amount' => number_format($expense->amount, 2),
                'amount' => $expense->amount,
                'createdAt' => $expense->created_at,
                'category' => $expense->category->name,
                'category_id' => $expense->category->id,
            ];
        }), 'activeFilterPeriod' => $filterPeriod, ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        $validated = $request->validated();

        $authenticatedUser = Auth::user();

        Expense::create([...$validated, 'user_id' => $authenticatedUser->id]);

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
