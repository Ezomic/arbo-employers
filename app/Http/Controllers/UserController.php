<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\IdentityClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request, IdentityClient $identity): Response
    {
        /** @var User $user */
        $user = $request->user();

        abort_if($user->employer_id === null, 403, 'Your account is not linked to an employer yet.');

        $users = rescue(
            fn () => $this->usersForOwnEmployer($identity, $user),
            [],
        );

        return Inertia::render('users/Index', [
            'users' => $users,
        ]);
    }

    public function store(Request $request, IdentityClient $identity): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_if($user->employer_id === null, 403, 'Your account is not linked to an employer yet.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
        ]);

        $created = $identity->createUser($user->tenant_id, $data['name'], $data['email'], 'employer', $user->employer_id);

        return to_route('users.index')->with('temporaryPassword', $created['temporary_password'] ?? null);
    }

    public function update(Request $request, string $uuid, IdentityClient $identity): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_if($user->employer_id === null, 403, 'Your account is not linked to an employer yet.');
        $this->abortUnlessOwnEmployerUser($identity, $user, $uuid);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email'],
        ]);

        $identity->updateUser($user->tenant_id, $uuid, $data);

        return to_route('users.index');
    }

    public function destroy(Request $request, string $uuid, IdentityClient $identity): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_if($user->employer_id === null, 403, 'Your account is not linked to an employer yet.');
        $this->abortUnlessOwnEmployerUser($identity, $user, $uuid);

        $identity->deleteUser($user->tenant_id, $uuid);

        return to_route('users.index');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function usersForOwnEmployer(IdentityClient $identity, User $user): array
    {
        return array_values(array_filter(
            $identity->getUsers($user->tenant_id, ['employer']),
            fn (array $identityUser) => $identityUser['scope_id'] === $user->employer_id,
        ));
    }

    /**
     * Identity's own API only enforces tenant isolation (this app is
     * trusted as a whole) — the per-employer boundary within a tenant has
     * to be checked here before we ever call update/delete.
     */
    private function abortUnlessOwnEmployerUser(IdentityClient $identity, User $user, string $uuid): void
    {
        $belongsToOwnEmployer = collect($this->usersForOwnEmployer($identity, $user))
            ->contains(fn (array $identityUser) => $identityUser['id'] === $uuid);

        abort_unless($belongsToOwnEmployer, 404);
    }
}
