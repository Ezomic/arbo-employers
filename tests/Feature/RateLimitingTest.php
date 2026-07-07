<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

test('the gdpr-export rate limiter is registered at 5 requests per day per user', function () {
    $limiter = RateLimiter::limiter('gdpr-export');

    expect($limiter)->not->toBeNull();

    $user = User::factory()->create();
    $request = Request::create('/self-service/gdpr-export', 'GET');
    $request->setUserResolver(fn () => $user);

    $limit = $limiter($request);

    expect($limit->maxAttempts)->toBe(5)
        ->and($limit->decaySeconds)->toBe(86400);
});
