<?php

use App\Models\Tenant;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

function ssoKeypair(): array
{
    $res = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
    openssl_pkey_export($res, $privateKey);
    $publicKey = openssl_pkey_get_details($res)['key'];

    return [$privateKey, $publicKey];
}

function ssoToken(string $privateKey, array $overrides = []): string
{
    $now = time();

    $payload = array_merge([
        'iss' => 'identity',
        'aud' => 'employers',
        'sub' => (string) Str::uuid(),
        'iat' => $now,
        'exp' => $now + 120,
        'email' => 'emma@client-company.test',
        'name' => 'Emma Employer',
        'role' => 'employer_contact',
        'tenant_id' => (string) Str::uuid(),
        'tenant_name' => 'Acme Arbodienst',
        'scope_id' => (string) Str::uuid(),
        'apps' => [],
    ], $overrides);

    return JWT::encode($payload, $privateKey, 'RS256');
}

test('visiting login redirects to identity sso/authorize with app and redirect_to params', function () {
    $response = $this->get('/login');

    $response->assertRedirect();
    $location = $response->headers->get('Location');

    expect($location)->toContain(config('sso.identity_base_url').'/sso/authorize')
        ->and($location)->toContain('app=employers')
        ->and($location)->toContain(urlencode(route('sso.callback')));
});

test('sso callback verifies the token, syncs the employer scope, and logs the user in', function () {
    [$privateKey, $publicKey] = ssoKeypair();
    Http::fake([config('sso.identity_base_url').'/.well-known/identity-public-key' => Http::response($publicKey)]);

    $token = ssoToken($privateKey);

    $response = $this->get('/sso/callback?token='.$token);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();

    $user = User::query()->where('email', 'emma@client-company.test')->first();
    expect($user)->not->toBeNull()
        ->and($user->employer_id)->not->toBeNull()
        ->and($user->current_role)->toBe('employer_contact');
    expect(Tenant::query()->where('name', 'Acme Arbodienst')->exists())->toBeTrue();
});

test('logging out destroys the local session and sends the browser on to end the Identity session too', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    // Without this second hop to Identity, its session would stay alive
    // and the next visit to any portal would silently re-authenticate —
    // logout wouldn't actually stick.
    $response->assertRedirect(config('sso.identity_base_url').'/sso/logout');
    $this->assertGuest();
});
