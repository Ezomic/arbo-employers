<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

test('index only lists users belonging to the acting employer', function () {
    $tenantId = (string) Str::uuid();
    $ownEmployerId = (string) Str::uuid();
    $otherEmployerId = (string) Str::uuid();

    Http::fake(['*/api/users*' => Http::response([
        ['id' => (string) Str::uuid(), 'name' => 'Own User', 'email' => 'own@acme.test', 'user_type_id' => 'employer', 'role_name' => null, 'scope_id' => $ownEmployerId, 'created_at' => now()->toIso8601String()],
        ['id' => (string) Str::uuid(), 'name' => 'Other User', 'email' => 'other@acme.test', 'user_type_id' => 'employer', 'role_name' => null, 'scope_id' => $otherEmployerId, 'created_at' => now()->toIso8601String()],
    ])]);

    $user = User::factory()->create(['tenant_id' => $tenantId, 'employer_id' => $ownEmployerId]);
    $this->actingAs($user);

    $response = $this->get('/users');

    $response->assertInertia(fn ($page) => $page
        ->component('users/Index')
        ->has('users', 1)
        ->where('users.0.name', 'Own User')
    );
});

test('a self-service employee user (no employer_id) is forbidden from the users page', function () {
    $user = User::factory()->create(['employer_id' => null, 'employee_id' => (string) Str::uuid()]);
    $this->actingAs($user);

    $response = $this->get('/users');

    $response->assertForbidden();
});

test('store creates the new user scoped to the acting employer', function () {
    $employerId = (string) Str::uuid();
    $user = User::factory()->create(['employer_id' => $employerId]);
    $this->actingAs($user);

    Http::fake(['*/api/users' => Http::response(['id' => (string) Str::uuid(), 'temporary_password' => 'temp-pass'])]);

    $response = $this->post('/users', ['name' => 'New Person', 'email' => 'new@acme.test']);

    $response->assertRedirect(route('users.index'));

    Http::assertSent(function ($request) use ($employerId) {
        return $request->method() === 'POST'
            && str_ends_with($request->url(), '/api/users')
            && $request['scope_id'] === $employerId;
    });
});

test('a self-service employee user cannot create a new user', function () {
    $user = User::factory()->create(['employer_id' => null, 'employee_id' => (string) Str::uuid()]);
    $this->actingAs($user);

    $response = $this->post('/users', ['name' => 'New Person', 'email' => 'new@acme.test']);

    $response->assertForbidden();
});

test('update rejects a uuid belonging to a different employer', function () {
    $ownEmployerId = (string) Str::uuid();
    $otherUuid = (string) Str::uuid();

    Http::fake(['*/api/users*' => Http::response([
        ['id' => $otherUuid, 'name' => 'Other User', 'email' => 'other@acme.test', 'user_type_id' => 'employer', 'role_name' => null, 'scope_id' => (string) Str::uuid(), 'created_at' => now()->toIso8601String()],
    ])]);

    $user = User::factory()->create(['employer_id' => $ownEmployerId]);
    $this->actingAs($user);

    $response = $this->put("/users/{$otherUuid}", ['name' => 'Renamed']);

    $response->assertNotFound();
});

test('destroy rejects a uuid belonging to a different employer', function () {
    $ownEmployerId = (string) Str::uuid();
    $otherUuid = (string) Str::uuid();

    Http::fake(['*/api/users*' => Http::response([
        ['id' => $otherUuid, 'name' => 'Other User', 'email' => 'other@acme.test', 'user_type_id' => 'employer', 'role_name' => null, 'scope_id' => (string) Str::uuid(), 'created_at' => now()->toIso8601String()],
    ])]);

    $user = User::factory()->create(['employer_id' => $ownEmployerId]);
    $this->actingAs($user);

    $response = $this->delete("/users/{$otherUuid}");

    $response->assertNotFound();
});
