<?php

namespace App\Http\Controllers;

use App\Enums\CaseType;
use App\Models\User;
use App\Services\CaseOfficersClient;
use App\Services\EmployerSyncService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmployerController extends Controller
{
    /**
     * Every employer_contact user belongs to exactly one Employer, so this
     * is a singular "my employer" page rather than a list.
     */
    public function show(Request $request, EmployerSyncService $sync, CaseOfficersClient $client): Response
    {
        /** @var User $user */
        $user = $request->user();

        abort_if($user->employer_id === null, 403, 'Your account is not linked to an employer yet.');

        $employer = $sync->sync($user->tenant_id, $user->employer_id);

        $openCases = $client->getOpenCases($user->tenant_id, $user->employer_id);

        $caseTypes = array_values(array_map(
            fn (CaseType $t) => ['value' => $t->value, 'label' => $t->label()],
            array_filter(CaseType::cases(), fn (CaseType $t) => $t->employerVisible()),
        ));

        return Inertia::render('employer/Show', [
            'employer' => $employer,
            'contracts' => $employer->contracts()->latest()->get(),
            'organizationalUnits' => $employer->organizationalUnits()->oldest()->get(),
            'employees' => $employer->employees()->with('organizationalUnit')->latest()->get(),
            'openCases' => array_values($openCases),
            'caseTypes' => $caseTypes,
        ]);
    }
}
