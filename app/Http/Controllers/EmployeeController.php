<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\OrganizationalUnit;
use App\Models\User;
use App\Services\CaseOfficersClient;
use App\Services\EmployerSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EmployeeController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $q = $request->validate(['q' => ['required', 'string', 'min:3']])['q'];

        $employees = Employee::query()
            ->where('employer_id', $user->employer_id)
            ->whereRaw("lower(first_name || ' ' || last_name) like ?", ['%'.strtolower($q).'%'])
            ->with('organizationalUnit')
            ->limit(15)
            ->get();

        $allUnits = OrganizationalUnit::query()
            ->where('employer_id', $user->employer_id)
            ->get()
            ->keyBy('id');

        return response()->json($employees->map(fn (Employee $e) => [
            'id' => $e->id,
            'label' => $this->buildLabel($e, $allUnits),
        ]));
    }

    /** @param Collection<string, OrganizationalUnit> $allUnits */
    private function buildLabel(Employee $employee, Collection $allUnits): string
    {
        $parts = [$employee->first_name.' '.$employee->last_name];

        $unit = $allUnits[$employee->organizational_unit_id] ?? null;
        if ($unit) {
            $parts[] = $unit->name;
            $legalEntity = $this->findLegalEntity($unit->parent_id, $allUnits);
            if ($legalEntity && $legalEntity !== $unit->name) {
                $parts[] = $legalEntity;
            }
        }

        return implode(' / ', $parts);
    }

    /** @param Collection<string, OrganizationalUnit> $allUnits */
    private function findLegalEntity(?string $unitId, Collection $allUnits): ?string
    {
        if ($unitId === null) return null;
        $unit = $allUnits[$unitId] ?? null;
        if ($unit === null) return null;
        if ($unit->is_legal_entity) return $unit->name;
        return $this->findLegalEntity($unit->parent_id, $allUnits);
    }

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
