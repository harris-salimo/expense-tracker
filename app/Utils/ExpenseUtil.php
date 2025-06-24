<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Collection; // Ensure Collection is imported

class ExpenseUtil
{
    /**
     * Calculates the total expenses for the past week.
     *
     * @param Collection $expenses A collection of expense objects, each with 'amount' and 'created_at'.
     * @return string The total expenses formatted to two decimal places.
     */
    public static function getPastWeekTotalExpenses(Collection $expenses): string
    {
        $startOfWeek = now()->subWeek()->startOfDay();
        $endOfWeek = now()->endOfDay();

        return number_format(
            $expenses->filter(function ($expense) use ($startOfWeek, $endOfWeek) {
                // Ensure 'created_at' is a Carbon instance for accurate date comparison
                $createdAt = Carbon::parse($expense->created_at);
                return $createdAt->between($startOfWeek, $endOfWeek);
            })->sum('amount'),
            2
        );
    }

    /**
     * Calculates the total expenses for the past month.
     *
     * @param Collection $expenses A collection of expense objects, each with 'amount' and 'created_at'.
     * @return string The total expenses formatted to two decimal places.
     */
    public static function getPastMonthTotalExpenses(Collection $expenses): string
    {
        $startOfMonth = now()->subMonth()->startOfDay();
        $endOfMonth = now()->endOfDay();

        return number_format(
            $expenses->filter(function ($expense) use ($startOfMonth, $endOfMonth) {
                $createdAt = Carbon::parse($expense->created_at);
                return $createdAt->between($startOfMonth, $endOfMonth);
            })->sum('amount'),
            2
        );
    }

    /**
     * Calculates the total expenses for the past year.
     *
     * @param Collection $expenses A collection of expense objects, each with 'amount' and 'created_at'.
     * @return string The total expenses formatted to two decimal places.
     */
    public static function getPastYearTotalExpenses(Collection $expenses): string
    {
        $startOfYear = now()->subYear()->startOfDay();
        $endOfYear = now()->endOfDay();

        return number_format(
            $expenses->filter(function ($expense) use ($startOfYear, $endOfYear) {
                $createdAt = Carbon::parse($expense->created_at);
                return $createdAt->between($startOfYear, $endOfYear);
            })->sum('amount'),
            2
        );
    }

    /**
     * Retrieves monthly expenses for the current year.
     *
     * @param Collection $expenses A collection of expense objects, each with 'amount' and 'created_at'.
     * @return array An array where each element represents a month's expenses,
     * with 'name' (month abbreviation) and 'total' (formatted string).
     */
    public static function getMonthlyExpenses(Collection $expenses): array
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

            // Filter expenses for the specific month and sum their amounts
            $total = $expenses->filter(function ($expense) use ($startOfMonth, $endOfMonth) {
                $createdAt = Carbon::parse($expense->created_at);
                return $createdAt->between($startOfMonth, $endOfMonth);
            })->sum('amount');

            $monthlyData[] = [
                'name' => $monthName, // Changed from 'month' to 'name' to match test expectation
                'total' => number_format($total, 2), // Formatted as string to match test expectation
            ];
        }

        return $monthlyData;
    }
}
