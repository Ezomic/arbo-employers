<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RobbinThijssen\IdentitySsoKit\Sso\SsoTokenVerifier;

class SsoCallbackController extends Controller
{
    public function __construct(private readonly SsoTokenVerifier $verifier) {}

    /**
     * This app has its own callback (rather than the shared package's
     * generic one) because it also needs to sync `employer_id` — the
     * specific Employer record (owned by Case Officers) this
     * employer_contact user represents, carried in the token's `scope_id`.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $verified = $this->verifier->verify($request->query('token', ''));

        if ($verified->tenantId !== null) {
            Tenant::query()->updateOrCreate(
                ['id' => $verified->tenantId],
                ['name' => $verified->tenantName ?? $verified->tenantId],
            );
        }

        $isEmployee = $verified->scopeId !== null
            && Employee::query()->where('id', $verified->scopeId)->exists();

        $user = User::query()->updateOrCreate(
            ['id' => $verified->userUuid],
            [
                'name' => $verified->name,
                'email' => $verified->email,
                'current_role' => $verified->role,
                'tenant_id' => $verified->tenantId,
                'employer_id' => $isEmployee ? null : $verified->scopeId,
                'employee_id' => $isEmployee ? $verified->scopeId : null,
                'accessible_apps' => $verified->accessibleApps,
                'identity_synced_at' => now(),
            ],
        );

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
