<?php

use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

test('a user without view-employees cannot search employees', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/employer/employees/search?q=jan');

    $response->assertForbidden();
});

test('a user with view-employees can search employees', function () {
    $user = User::factory()->create();
    RolePermission::query()->create([
        'tenant_id' => $user->tenant_id,
        'role_name' => $user->current_role,
        'permission' => 'View employees',
    ]);
    $this->actingAs($user);

    $response = $this->get('/employer/employees/search?q=jan');

    $response->assertOk();
});

test('a user without manage-employees cannot add an employee', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/employer/employees', [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'organizational_unit_id' => (string) Str::uuid(),
    ]);

    $response->assertForbidden();
});

test('a user with manage-employees can add an employee', function () {
    $user = User::factory()->create();
    RolePermission::query()->create([
        'tenant_id' => $user->tenant_id,
        'role_name' => $user->current_role,
        'permission' => 'Manage employees',
    ]);

    Http::fake([
        "*/employers/{$user->employer_id}/employees*" => Http::response([]),
        "*/employers/{$user->employer_id}/contracts*" => Http::response([]),
        "*/employers/{$user->employer_id}/organizational-units*" => Http::response([]),
        "*/employers/{$user->employer_id}/contact-persons*" => Http::response([]),
        "*/employers/{$user->employer_id}?*" => Http::response([
            'id' => $user->employer_id,
            'tenant_id' => $user->tenant_id,
            'name' => 'Acme',
        ]),
    ]);

    $this->actingAs($user);

    $response = $this->post('/employer/employees', [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'organizational_unit_id' => (string) Str::uuid(),
    ]);

    $response->assertRedirect(route('employer.show'));
});
