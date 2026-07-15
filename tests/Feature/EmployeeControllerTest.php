<?php

use App\Models\Employee;
use App\Models\Employer;
use App\Models\OrganizationalUnit;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
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

test('adding an employee syncs personal data and address back from case-officers', function () {
    $user = User::factory()->create();
    RolePermission::query()->create([
        'tenant_id' => $user->tenant_id,
        'role_name' => $user->current_role,
        'permission' => 'Manage employees',
    ]);

    Employer::query()->create(['id' => $user->employer_id, 'tenant_id' => $user->tenant_id, 'name' => 'Acme']);
    $unit = OrganizationalUnit::query()->create(['id' => (string) Str::uuid(), 'employer_id' => $user->employer_id, 'name' => 'HQ']);

    $employeeId = (string) Str::uuid();
    $addressId = (string) Str::uuid();
    $unitId = $unit->id;

    Http::fake([
        "*/employers/{$user->employer_id}/employees*" => Http::response([
            [
                'id' => $employeeId,
                'organizational_unit_id' => $unitId,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => null,
                'employee_number' => null,
                'status' => 'active',
                'date_of_birth' => '1990-01-01',
                'gender' => 'female',
                'nationality' => 'NLD',
                'address' => [
                    'id' => $addressId,
                    'address_line_1' => 'Kerkstraat 1',
                    'address_line_2' => null,
                    'postal_code' => '1234AB',
                    'city' => 'Amsterdam',
                    'country' => 'NL',
                ],
            ],
        ]),
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

    $this->post('/employer/employees', [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'organizational_unit_id' => $unitId,
    ]);

    $employee = Employee::query()->findOrFail($employeeId);
    expect($employee->gender)->toBe('female')
        ->and($employee->nationality)->toBe('NLD')
        ->and($employee->date_of_birth->toDateString())->toBe('1990-01-01')
        ->and($employee->address)->not->toBeNull()
        ->and($employee->address->city)->toBe('Amsterdam');
});

test('the employees table has no bsn column', function () {
    expect(Schema::hasColumn('employees', 'bsn'))->toBeFalse();
});

test('a user without manage-employees cannot update an employee', function () {
    $user = User::factory()->create();
    Employer::query()->create(['id' => $user->employer_id, 'tenant_id' => $user->tenant_id, 'name' => 'Acme']);
    $employee = Employee::query()->create([
        'id' => (string) Str::uuid(),
        'employer_id' => $user->employer_id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);
    $this->actingAs($user);

    $response = $this->put("/employer/employees/{$employee->id}", [
        'first_name' => 'Jane',
        'last_name' => 'Roe',
        'organizational_unit_id' => (string) Str::uuid(),
    ]);

    $response->assertForbidden();
});

test('a user with manage-employees can update an employee', function () {
    $user = User::factory()->create();
    RolePermission::query()->create([
        'tenant_id' => $user->tenant_id,
        'role_name' => $user->current_role,
        'permission' => 'Manage employees',
    ]);

    Employer::query()->create(['id' => $user->employer_id, 'tenant_id' => $user->tenant_id, 'name' => 'Acme']);
    $unit = OrganizationalUnit::query()->create(['id' => (string) Str::uuid(), 'employer_id' => $user->employer_id, 'name' => 'HQ']);

    $employeeId = (string) Str::uuid();
    $unitId = $unit->id;

    Employee::query()->create([
        'id' => $employeeId,
        'employer_id' => $user->employer_id,
        'organizational_unit_id' => $unitId,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);

    Http::fake([
        "*/employers/{$user->employer_id}/employees/{$employeeId}*" => Http::response([]),
        "*/employers/{$user->employer_id}/employees*" => Http::response([
            [
                'id' => $employeeId,
                'organizational_unit_id' => $unitId,
                'first_name' => 'Jane',
                'last_name' => 'Roe',
                'email' => null,
                'employee_number' => null,
                'status' => 'active',
                'date_of_birth' => null,
                'gender' => null,
                'nationality' => null,
            ],
        ]),
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

    $response = $this->put("/employer/employees/{$employeeId}", [
        'first_name' => 'Jane',
        'last_name' => 'Roe',
        'organizational_unit_id' => $unitId,
    ]);

    $response->assertRedirect(route('employer.show'));
    expect(Employee::query()->findOrFail($employeeId)->last_name)->toBe('Roe');
});
