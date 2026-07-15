<?php

use App\Models\Address;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\OrganizationalUnit;
use App\Services\EmployerSyncService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

test('the employer sync service pulls employee personal data and address from case-officers', function () {
    $tenantId = (string) Str::uuid();
    $employerId = (string) Str::uuid();
    $employeeId = (string) Str::uuid();
    $addressId = (string) Str::uuid();

    Employer::query()->create(['id' => $employerId, 'tenant_id' => $tenantId, 'name' => 'Acme Corp']);
    $unit = OrganizationalUnit::query()->create(['id' => (string) Str::uuid(), 'employer_id' => $employerId, 'name' => 'HQ']);

    Http::fake([
        '*/employees*' => Http::response([
            [
                'id' => $employeeId,
                'organizational_unit_id' => $unit->id,
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
        '*/contact-persons*' => Http::response([]),
        '*/contracts*' => Http::response([]),
        '*/organizational-units*' => Http::response([]),
        "*/employers/{$employerId}?*" => Http::response([
            'id' => $employerId,
            'tenant_id' => $tenantId,
            'name' => 'Acme Corp',
        ]),
    ]);

    app(EmployerSyncService::class)->sync($tenantId, $employerId);

    $employee = Employee::query()->findOrFail($employeeId);
    expect($employee->gender)->toBe('female')
        ->and($employee->nationality)->toBe('NLD')
        ->and($employee->date_of_birth->toDateString())->toBe('1990-01-01')
        ->and($employee->address)->not->toBeNull()
        ->and($employee->address->city)->toBe('Amsterdam');
});

test('the employer sync service creates no address when case-officers reports none', function () {
    $tenantId = (string) Str::uuid();
    $employerId = (string) Str::uuid();
    $employeeId = (string) Str::uuid();

    Employer::query()->create(['id' => $employerId, 'tenant_id' => $tenantId, 'name' => 'Acme Corp']);
    $unit = OrganizationalUnit::query()->create(['id' => (string) Str::uuid(), 'employer_id' => $employerId, 'name' => 'HQ']);

    Http::fake([
        '*/employees*' => Http::response([
            [
                'id' => $employeeId,
                'organizational_unit_id' => $unit->id,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => null,
                'employee_number' => null,
                'status' => 'active',
            ],
        ]),
        '*/contact-persons*' => Http::response([]),
        '*/contracts*' => Http::response([]),
        '*/organizational-units*' => Http::response([]),
        "*/employers/{$employerId}?*" => Http::response([
            'id' => $employerId,
            'tenant_id' => $tenantId,
            'name' => 'Acme Corp',
        ]),
    ]);

    app(EmployerSyncService::class)->sync($tenantId, $employerId);

    expect(Employee::query()->findOrFail($employeeId)->address)->toBeNull();
});

test('the employer sync service deletes a local address once case-officers no longer reports it', function () {
    $tenantId = (string) Str::uuid();
    $employerId = (string) Str::uuid();
    $employeeId = (string) Str::uuid();

    Employer::query()->create(['id' => $employerId, 'tenant_id' => $tenantId, 'name' => 'Acme Corp']);
    $unit = OrganizationalUnit::query()->create(['id' => (string) Str::uuid(), 'employer_id' => $employerId, 'name' => 'HQ']);

    $employee = Employee::query()->create([
        'id' => $employeeId,
        'employer_id' => $employerId,
        'organizational_unit_id' => $unit->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);
    $employee->address()->create([
        'id' => (string) Str::uuid(),
        'address_line_1' => 'Kerkstraat 1',
        'postal_code' => '1234AB',
        'city' => 'Amsterdam',
        'country' => 'NL',
    ]);

    Http::fake([
        '*/employees*' => Http::response([
            [
                'id' => $employeeId,
                'organizational_unit_id' => $unit->id,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => null,
                'employee_number' => null,
                'status' => 'active',
            ],
        ]),
        '*/contact-persons*' => Http::response([]),
        '*/contracts*' => Http::response([]),
        '*/organizational-units*' => Http::response([]),
        "*/employers/{$employerId}?*" => Http::response([
            'id' => $employerId,
            'tenant_id' => $tenantId,
            'name' => 'Acme Corp',
        ]),
    ]);

    app(EmployerSyncService::class)->sync($tenantId, $employerId);

    expect(Employee::query()->findOrFail($employeeId)->address)->toBeNull();
    expect(Address::query()->count())->toBe(0);
});
