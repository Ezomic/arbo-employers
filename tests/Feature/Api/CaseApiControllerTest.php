<?php

use App\Models\CaseFile;
use App\Models\Employee;
use App\Models\Employer;
use Illuminate\Support\Str;

function apiHeaders(): array
{
    return ['Authorization' => 'Bearer '.config('services.case_officers.inbound_token')];
}

test('sync derives employer_id from the employee and defaults case_type', function () {
    $tenantId = (string) Str::uuid();
    $employer = Employer::query()->create(['id' => (string) Str::uuid(), 'tenant_id' => $tenantId, 'name' => 'Acme']);
    $employee = Employee::query()->create([
        'id' => (string) Str::uuid(),
        'employer_id' => $employer->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);
    $caseId = (string) Str::uuid();

    $response = $this->withHeaders(apiHeaders())->putJson("/api/cases/{$caseId}", [
        'tenant_id' => $tenantId,
        'employee_id' => $employee->id,
        'status' => 'open',
        'opened_at' => '2026-07-01',
    ]);

    $response->assertNoContent();

    $case = CaseFile::query()->findOrFail($caseId);
    expect($case->employer_id)->toBe($employer->id)
        ->and($case->case_type)->toBe('verzuim');
});

test('sync rejects an employee that does not belong to the given tenant', function () {
    $employer = Employer::query()->create(['id' => (string) Str::uuid(), 'tenant_id' => (string) Str::uuid(), 'name' => 'Acme']);
    $employee = Employee::query()->create([
        'id' => (string) Str::uuid(),
        'employer_id' => $employer->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);

    $response = $this->withHeaders(apiHeaders())->putJson('/api/cases/'.(string) Str::uuid(), [
        'tenant_id' => (string) Str::uuid(),
        'employee_id' => $employee->id,
        'status' => 'open',
        'opened_at' => '2026-07-01',
    ]);

    $response->assertNotFound();
});

test('sync rejects requests without a valid bearer token', function () {
    $response = $this->putJson('/api/cases/'.(string) Str::uuid(), [
        'tenant_id' => (string) Str::uuid(),
        'employee_id' => (string) Str::uuid(),
        'status' => 'open',
        'opened_at' => '2026-07-01',
    ]);

    $response->assertUnauthorized();
});
