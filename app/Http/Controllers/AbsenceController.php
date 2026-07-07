<?php

namespace App\Http\Controllers;

use App\Enums\CaseType;
use App\Models\CaseFile;
use App\Models\User;
use App\Services\CaseOfficersClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

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
            'case_type' => ['required', Rule::enum(CaseType::class)->only($this->employerVisibleCaseTypes())],
            'start_date' => ['required', 'date'],
        ]);

        $case = CaseFile::query()->create([
            'id' => (string) Str::uuid7(),
            'tenant_id' => $user->tenant_id,
            'employer_id' => $user->employer_id,
            'employee_id' => $data['employee_id'],
            'case_type' => $data['case_type'],
            'status' => 'open',
            'opened_at' => $data['start_date'],
        ]);

        $this->syncToCaseOfficers($client, $case, 'store');

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Absence reported — your case officer has been notified.',
        ]);

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
            ->where('employer_id', $user->employer_id)
            ->where('status', 'open')
            ->findOrFail($case);

        $caseFile->update(['expected_return_date' => $data['expected_return_date'] ?? null]);

        $this->syncToCaseOfficers($client, $caseFile->fresh(), 'mutate');

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Mutation saved.',
        ]);

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
            ->where('employer_id', $user->employer_id)
            ->where('status', 'open')
            ->findOrFail($case);

        $caseFile->update([
            'status' => 'closed',
            'closed_at' => $data['recovery_date'],
        ]);

        $this->syncToCaseOfficers($client, $caseFile->fresh(), 'close');

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Recovery registered — the case has been closed.',
        ]);

        return to_route('employer.show');
    }

    /**
     * @return array<int, CaseType>
     */
    private function employerVisibleCaseTypes(): array
    {
        return array_values(array_filter(CaseType::cases(), fn (CaseType $type) => $type->employerVisible()));
    }

    private function syncToCaseOfficers(CaseOfficersClient $client, CaseFile $case, string $action): void
    {
        try {
            $client->syncCase($case);
        } catch (\Throwable $e) {
            Log::warning('Case Officers sync failed after absence '.$action, [
                'case_id' => $case->id,
                'tenant_id' => $case->tenant_id,
                'action' => $action,
                'error' => $e->getMessage(),
            ]);

            report($e);
        }
    }
}
