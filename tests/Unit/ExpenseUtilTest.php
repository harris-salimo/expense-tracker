<?php

use App\Utils\ExpenseUtil;

test('should return past week total expenses', function () {
    expect(ExpenseUtil::getPastWeekTotalExpenses())->toBe('100.00');
});

test('should return past month total expenses', function () {
    expect(ExpenseUtil::getPastMonthTotalExpenses())->toBe('400.00');
});

test('should return past year total expenses', function () {
    expect(ExpenseUtil::getPastYearTotalExpenses())->toBe('4800.00');
});

test('should return monthly expenses', function () {
    expect(ExpenseUtil::getMonthlyExpenses())->toBe([
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
    ]);
});
