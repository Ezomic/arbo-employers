<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CaseOfficersClient;
use App\Services\EmployerSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function store(Request $request, CaseOfficersClient $client, EmployerSyncService $sync): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'employee_number' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'organizational_unit_id' => ['required', 'uuid'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $client->createEmployee($user->tenant_id, $user->employer_id, $data);

        $sync->sync($user->tenant_id, $user->employer_id);

        return to_route('employer.show');
    }
}
