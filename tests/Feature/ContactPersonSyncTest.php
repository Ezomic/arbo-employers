<?php

use App\Services\EmployerSyncService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

test('the employer sync service pulls contact persons from case-officers', function () {
    $tenantId = (string) Str::uuid();
    $employerId = (string) Str::uuid();
    $contactId = (string) Str::uuid();

    Http::fake([
        '*/contact-persons*' => Http::response([
            ['id' => $contactId, 'name' => 'Jane Doe', 'email' => null, 'phone' => null, 'job_title' => null],
        ]),
        '*/contracts*' => Http::response([]),
        '*/employees*' => Http::response([]),
        '*/organizational-units*' => Http::response([]),
        "*/employers/{$employerId}?*" => Http::response([
            'id' => $employerId,
            'tenant_id' => $tenantId,
            'name' => 'Acme Corp',
        ]),
    ]);

    $employer = app(EmployerSyncService::class)->sync($tenantId, $employerId);

    expect($employer->contactPersons()->where('id', $contactId)->exists())->toBeTrue();
});
