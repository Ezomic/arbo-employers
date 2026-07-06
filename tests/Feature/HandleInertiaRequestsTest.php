<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('shared caseTypes prop only exposes employer-visible case types', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Http::fake([
        "*/employers/{$user->employer_id}?*" => Http::response(['id' => $user->employer_id, 'tenant_id' => $user->tenant_id, 'name' => 'Acme']),
        '*/contracts*' => Http::response([]),
        '*/employees*' => Http::response([]),
        '*/organizational-units*' => Http::response([]),
        '*/contact-persons*' => Http::response([]),
    ]);

    $response = $this->get(route('employer.show'));

    $response->assertInertia(fn ($page) => $page
        ->has('caseTypes', 3)
        ->where('caseTypes.0.value', 'verzuim')
    );
});
