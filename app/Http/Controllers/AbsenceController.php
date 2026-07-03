<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CaseOfficersClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AbsenceController extends Controller
{
    /**
     * Registers the start of an absence course for one of this employer's
     * own employees, which is what actually creates the case on Case
     * Officers' side — this app never creates a case directly, it only
     * ever asks Case Officers to.
     */
    public function store(Request $request, CaseOfficersClient $client): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'employee_id' => [
                'required',
                'uuid',
                Rule::exists('employees', 'id')->where('employer_id', $user->employer_id),
            ],
            'start_date' => ['required', 'date'],
        ]);

        $client->createCase($user->tenant_id, $data['employee_id'], $data['start_date']);

        return to_route('employer.show');
    }
}
