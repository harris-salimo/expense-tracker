<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::factory()->create(['name' => 'Admin']);
        User::factory()->for($adminRole)->create([
            'name' => 'Admin',
            'email' => 'admin@expense-tracker.com',
        ]);

        $userRole = Role::factory()->create(['name' => 'User']);
        $users = User::factory(10)->for($userRole)->create();
        $categories = Category::factory(5)->create();

        foreach ($categories as $category) {
            foreach ($users as $user) {
                Expense::factory(2)->for($user)->for($category)->create();
            }
        }
    }
}
