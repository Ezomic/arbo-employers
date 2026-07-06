<?php

use App\Models\ContactPerson;
use App\Models\Employer;
use Illuminate\Support\Str;

test('sync upserts incoming contact persons and removes stale ones', function () {
    $tenantId = (string) Str::uuid();
    $employer = Employer::query()->create(['id' => (string) Str::uuid(), 'tenant_id' => $tenantId, 'name' => 'Acme']);

    $stale = ContactPerson::query()->create([
        'id' => (string) Str::uuid(),
        'employer_id' => $employer->id,
        'name' => 'Old Contact',
    ]);

    $keptId = (string) Str::uuid();

    $response = $this->withHeaders(['Authorization' => 'Bearer '.config('services.case_officers.inbound_token')])
        ->putJson("/api/employers/{$employer->id}/contact-persons", [
            'tenant_id' => $tenantId,
            'contact_persons' => [
                ['id' => $keptId, 'name' => 'Jane Doe', 'email' => 'jane@acme.test', 'phone' => null, 'job_title' => 'HR'],
            ],
        ]);

    $response->assertNoContent();

    expect(ContactPerson::query()->find($stale->id))->toBeNull()
        ->and(ContactPerson::query()->find($keptId)?->name)->toBe('Jane Doe');
});

test('sync 404s for an employer in a different tenant', function () {
    $employer = Employer::query()->create(['id' => (string) Str::uuid(), 'tenant_id' => (string) Str::uuid(), 'name' => 'Acme']);

    $response = $this->withHeaders(['Authorization' => 'Bearer '.config('services.case_officers.inbound_token')])
        ->putJson("/api/employers/{$employer->id}/contact-persons", [
            'tenant_id' => (string) Str::uuid(),
            'contact_persons' => [],
        ]);

    $response->assertNotFound();
});
