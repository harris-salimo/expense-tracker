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

        $latestExpenses = [];
        $expenses = Expense::with('category');

        if ($authenticatedUser->role->name !== 'Admin') {
            $latestExpenses = $expenses->where('user_id', $authenticatedUser->id);
        } else {
            $latestExpenses = $expenses;
        }

        return Inertia::render('Dashboard', [
            'latestExpenses' => $latestExpenses->orderByDesc('created_at')->take(13)->get()->map(function (Expense $expense) {
                return [
                    'id' => $expense->id,
                    'amount' => number_format($expense->amount, 2),
                    'createdAt' => $expense->created_at,
                    'category' => $expense->category->name,
                ];
            }),
            'pastWeekTotalExpenses' => ExpenseUtil::getPastWeekTotalExpenses($latestExpenses),
            'pastMonthTotalExpenses' => ExpenseUtil::getPastMonthTotalExpenses($latestExpenses),
            'pastYearTotalExpenses' => ExpenseUtil::getPastYearTotalExpenses($latestExpenses),
            'monthlyExpenses' => ExpenseUtil::getMonthlyExpenses($latestExpenses),
        ]);
    }
}
