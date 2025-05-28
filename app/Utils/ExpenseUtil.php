<?php

namespace App\Utils;

use Carbon\Carbon;

class ExpenseUtil
{
    public static function getPastWeekTotalExpenses(mixed $expenses)
    {
        return number_format(
            $expenses
                ->where('created_at', '>=', now()->subWeek())
                ->where('created_at', '<=', now())
                ->sum('amount'),
            2
        );
    }

    public static function getPastMonthTotalExpenses(mixed $expenses)
    {
        return number_format(
            $expenses
                ->where('created_at', '>=', now()->subMonth())
                ->where('created_at', '<=', now())
                ->sum('amount'),
            2
        );
    }

    public static function getPastYearTotalExpenses(mixed $expenses)
    {
        return number_format(
            $expenses
                ->where('created_at', '>=', now()->subYear())
                ->where('created_at', '<=', now())
                ->sum('amount'),
            2
        );
    }

    public static function getMonthlyExpenses(mixed $expenses)
    {
        $currentYear = now()->year;
        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ];

        $monthlyData = [];

        foreach ($months as $monthNumber => $monthName) {
            $startOfMonth = Carbon::create($currentYear, $monthNumber, 1)->startOfMonth();
            $endOfMonth = Carbon::create($currentYear, $monthNumber, 1)->endOfMonth();

            $total = $expenses
                ->where('created_at', '>=', $startOfMonth)
                ->where('created_at', '<=', $endOfMonth)
                ->sum('amount');

            $monthlyData[] = [
                'month' => $monthName,
                'expenses' => (float) $total,
            ];
        }

        return $monthlyData;
    }
}
