<?php

use App\Models\Role;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $role = Role::factory()->create(['name' => 'User']);
    $user = User::factory()->for($role)->create();
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});
