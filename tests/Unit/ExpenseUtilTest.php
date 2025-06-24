<?php

use App\Utils\ExpenseUtil;
use Carbon\Carbon;
use Illuminate\Support\Collection;

function createExpense(float $amount, Carbon $date): object
{
    return (object) ['amount' => $amount, 'created_at' => $date];
}

test('should return past week total expenses', function () {
    $expenses = new Collection([
        createExpense(40.00, now()->subDays(2)),
        createExpense(60.00, now()->subDays(5)),
        createExpense(150.00, now()->subDays(8)),
    ]);

    expect(ExpenseUtil::getPastWeekTotalExpenses($expenses))->toBe('100.00');
});

test('should return past month total expenses', function () {
    $expenses = new Collection([
        createExpense(100.00, now()->subDays(5)),
        createExpense(100.00, now()->subDays(10)),
        createExpense(100.00, now()->subDays(20)),
        createExpense(100.00, now()->subDays(25)),
        createExpense(200.00, now()->subDays(35)),
    ]);

    expect(ExpenseUtil::getPastMonthTotalExpenses($expenses))->toBe('400.00');
});

test('should return past year total expenses', function () {
    $expenses = new Collection;
    for ($i = 0; $i < 12; $i++) {
        $expenses->push(createExpense(400.00, now()->subMonths($i)->startOfMonth()->addDays(10)));
    }

    $expenses->push(createExpense(1000.00, now()->subMonths(13)->startOfMonth()));

    expect(ExpenseUtil::getPastYearTotalExpenses($expenses))->toBe('4800.00');
});

test('should return monthly expenses for the current year', function () {
    $currentYear = now()->year;
    $expenses = new Collection;

    for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
        $expenses->push(createExpense(100.00, Carbon::create($currentYear, $monthNum, 5)));
        $expenses->push(createExpense(100.00, Carbon::create($currentYear, $monthNum, 15)));
        $expenses->push(createExpense(100.00, Carbon::create($currentYear, $monthNum, 20)));
        $expenses->push(createExpense(100.00, Carbon::create($currentYear, $monthNum, 25)));
    }

    $expenses->push(createExpense(500.00, Carbon::create($currentYear - 1, 7, 10)));

    $expectedMonthlyExpenses = [
        ['name' => 'Jan', 'total' => '400.00'],
        ['name' => 'Feb', 'total' => '400.00'],
        ['name' => 'Mar', 'total' => '400.00'],
        ['name' => 'Apr', 'total' => '400.00'],
        ['name' => 'May', 'total' => '400.00'],
        ['name' => 'Jun', 'total' => '400.00'],
        ['name' => 'Jul', 'total' => '400.00'],
        ['name' => 'Aug', 'total' => '400.00'],
        ['name' => 'Sep', 'total' => '400.00'],
        ['name' => 'Oct', 'total' => '400.00'],
        ['name' => 'Nov', 'total' => '400.00'],
        ['name' => 'Dec', 'total' => '400.00'],
    ];

    expect(ExpenseUtil::getMonthlyExpenses($expenses))->toEqual($expectedMonthlyExpenses);
});
