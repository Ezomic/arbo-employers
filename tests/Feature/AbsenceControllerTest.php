<?php

use App\Models\CaseFile;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

function employerUser(string $tenantId, string $employerId): User
{
    return User::factory()->create(['tenant_id' => $tenantId, 'employer_id' => $employerId]);
}

test('store creates a case scoped to the acting employer and syncs it', function () {
    Http::fake(['*/api/cases/*' => Http::response([])]);

    $tenantId = (string) Str::uuid();
    $employer = Employer::query()->create(['id' => (string) Str::uuid(), 'tenant_id' => $tenantId, 'name' => 'Acme']);
    $employee = Employee::query()->create(['id' => (string) Str::uuid(), 'employer_id' => $employer->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);
    $user = employerUser($tenantId, $employer->id);
    $this->actingAs($user);

    $response = $this->post('/employer/absences', [
        'employee_id' => $employee->id,
        'case_type' => 'verzuim',
        'start_date' => '2026-07-01',
    ]);

    $response->assertRedirect(route('employer.show'));

    $case = CaseFile::query()->where('employee_id', $employee->id)->firstOrFail();
    expect($case->employer_id)->toBe($employer->id);
});

test('store rejects a case_type that is not employer-visible', function () {
    $tenantId = (string) Str::uuid();
    $employer = Employer::query()->create(['id' => (string) Str::uuid(), 'tenant_id' => $tenantId, 'name' => 'Acme']);
    $employee = Employee::query()->create(['id' => (string) Str::uuid(), 'employer_id' => $employer->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);
    $user = employerUser($tenantId, $employer->id);
    $this->actingAs($user);

    $response = $this->post('/employer/absences', [
        'employee_id' => $employee->id,
        'case_type' => 'pmo',
        'start_date' => '2026-07-01',
    ]);

    $response->assertSessionHasErrors('case_type');
    expect(CaseFile::query()->where('employee_id', $employee->id)->exists())->toBeFalse();
});

test('mutate cannot touch a case belonging to a different employer', function () {
    $tenantId = (string) Str::uuid();
    $ownEmployer = Employer::query()->create(['id' => (string) Str::uuid(), 'tenant_id' => $tenantId, 'name' => 'Own Co']);
    $otherEmployer = Employer::query()->create(['id' => (string) Str::uuid(), 'tenant_id' => $tenantId, 'name' => 'Other Co']);
    $otherEmployee = Employee::query()->create(['id' => (string) Str::uuid(), 'employer_id' => $otherEmployer->id, 'first_name' => 'Bob', 'last_name' => 'Other']);

    $otherCase = CaseFile::query()->create([
        'id' => (string) Str::uuid(),
        'tenant_id' => $tenantId,
        'employer_id' => $otherEmployer->id,
        'employee_id' => $otherEmployee->id,
        'case_type' => 'verzuim',
        'status' => 'open',
        'opened_at' => '2026-07-01',
    ]);

    $user = employerUser($tenantId, $ownEmployer->id);
    $this->actingAs($user);

    $response = $this->post("/employer/absences/{$otherCase->id}/mutate", [
        'expected_return_date' => '2026-07-15',
    ]);

    $response->assertNotFound();
    expect($otherCase->fresh()->expected_return_date)->toBeNull();
});

test('close cannot touch a case belonging to a different employer', function () {
    $tenantId = (string) Str::uuid();
    $ownEmployer = Employer::query()->create(['id' => (string) Str::uuid(), 'tenant_id' => $tenantId, 'name' => 'Own Co']);
    $otherEmployer = Employer::query()->create(['id' => (string) Str::uuid(), 'tenant_id' => $tenantId, 'name' => 'Other Co']);
    $otherEmployee = Employee::query()->create(['id' => (string) Str::uuid(), 'employer_id' => $otherEmployer->id, 'first_name' => 'Bob', 'last_name' => 'Other']);

    $otherCase = CaseFile::query()->create([
        'id' => (string) Str::uuid(),
        'tenant_id' => $tenantId,
        'employer_id' => $otherEmployer->id,
        'employee_id' => $otherEmployee->id,
        'case_type' => 'verzuim',
        'status' => 'open',
        'opened_at' => '2026-07-01',
    ]);

    $user = employerUser($tenantId, $ownEmployer->id);
    $this->actingAs($user);

    $response = $this->post("/employer/absences/{$otherCase->id}/close", [
        'recovery_date' => '2026-07-15',
    ]);

    $response->assertNotFound();
    expect($otherCase->fresh()->status)->toBe('open');
});
