<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Utils\ExpenseUtil;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $authenticatedUser = $request->user()->loadMissing('role');

        $expensesQuery = Expense::with('category');

        if ($authenticatedUser->role->name !== 'Admin') {
            $expensesQuery->where('user_id', $authenticatedUser->id);
        }

        $allUserExpenses = $expensesQuery->get();

        return Inertia::render('Dashboard', [
            'latestExpenses' => $allUserExpenses->sortByDesc('created_at')->take(13)->map(function (Expense $expense) {
                return [
                    'id' => $expense->id,
                    // 'amount' => number_format($expense->amount, 2),
                    'amount' => $expense->amount,
                    'createdAt' => $expense->created_at,
                    'category' => $expense->category->name,
                ];
            }),
            'pastWeekTotalExpenses' => ExpenseUtil::getPastWeekTotalExpenses($allUserExpenses),
            'pastMonthTotalExpenses' => ExpenseUtil::getPastMonthTotalExpenses($allUserExpenses),
            'pastYearTotalExpenses' => ExpenseUtil::getPastYearTotalExpenses($allUserExpenses),
            'monthlyExpenses' => ExpenseUtil::getMonthlyExpenses($allUserExpenses),
        ]);
    }
}
