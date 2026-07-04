<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use App\Models\User;
use App\Services\CaseOfficersClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AbsenceController extends Controller
{
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

        $case = CaseFile::query()->create([
            'id' => (string) Str::uuid(),
            'tenant_id' => $user->tenant_id,
            'employee_id' => $data['employee_id'],
            'status' => 'open',
            'opened_at' => $data['start_date'],
        ]);

        rescue(fn () => $client->syncCase($case));

        return to_route('employer.show');
    }

    public function mutate(Request $request, string $case, CaseOfficersClient $client): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'expected_return_date' => ['nullable', 'date'],
        ]);

        $caseFile = CaseFile::query()
            ->where('tenant_id', $user->tenant_id)
            ->where('status', 'open')
            ->findOrFail($case);

        $caseFile->update(['expected_return_date' => $data['expected_return_date'] ?? null]);

        rescue(fn () => $client->syncCase($caseFile->fresh()));

        return to_route('employer.show');
    }

    public function close(Request $request, string $case, CaseOfficersClient $client): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'recovery_date' => ['required', 'date'],
        ]);

        $caseFile = CaseFile::query()
            ->where('tenant_id', $user->tenant_id)
            ->where('status', 'open')
            ->findOrFail($case);

        $caseFile->update([
            'status' => 'closed',
            'closed_at' => $data['recovery_date'],
        ]);

        rescue(fn () => $client->syncCase($caseFile->fresh()));

        return to_route('employer.show');
    }
}
