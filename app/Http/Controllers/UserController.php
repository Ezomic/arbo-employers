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

        $users = rescue(
            fn () => $identity->getUsers($user->tenant_id, ['employer']),
            [],
        );

        return Inertia::render('users/Index', [
            'users' => $users,
            'employers' => \App\Models\Employer::query()->where('tenant_id', $user->tenant_id)->oldest('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request, IdentityClient $identity): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'scope_id' => ['nullable', 'uuid'],
        ]);

        $created = $identity->createUser($user->tenant_id, $data['name'], $data['email'], 'employer', $data['scope_id'] ?? null);

        return to_route('users.index')->with('temporaryPassword', $created['temporary_password'] ?? null);
    }

    public function update(Request $request, string $uuid, IdentityClient $identity): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email'],
            'scope_id' => ['sometimes', 'nullable', 'uuid'],
        ]);

        $identity->updateUser($uuid, $data);

        return to_route('users.index');
    }

    public function destroy(string $uuid, IdentityClient $identity): RedirectResponse
    {
        $identity->deleteUser($uuid);

        return to_route('users.index');
    }
}
