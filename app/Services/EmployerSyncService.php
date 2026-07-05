<?php

namespace App\Services;

use App\Models\ContactPerson;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\OrganizationalUnit;

/**
 * Pulls the current employer/contracts/organizational units/employees from
 * Case Officers (the canonical owner) and upserts them into this app's
 * local read-shadow, so its own pages render from a fast local query
 * rather than a live call.
 */
class EmployerSyncService
{
    public function __construct(private readonly CaseOfficersClient $client) {}

    public function sync(string $tenantId, string $employerId): Employer
    {
        $employerData = $this->client->getEmployer($tenantId, $employerId);

        $employer = Employer::query()->updateOrCreate(
            ['id' => $employerData['id']],
            [
                'tenant_id' => $employerData['tenant_id'],
                'name' => $employerData['name'],
                'address_line_1' => $employerData['address_line_1'] ?? null,
                'address_line_2' => $employerData['address_line_2'] ?? null,
                'postal_code' => $employerData['postal_code'] ?? null,
                'city' => $employerData['city'] ?? null,
            ],
        );

        foreach ($this->client->getContracts($tenantId, $employerId) as $contract) {
            Contract::query()->updateOrCreate(
                ['id' => $contract['id']],
                [
                    'employer_id' => $employer->id,
                    'contract_type_label' => $contract['contract_type_label'],
                    'start_date' => $contract['start_date'],
                    'end_date' => $contract['end_date'],
                    'status' => $contract['status'],
                ],
            );
        }

        // Oldest-first from Case Officers means every parent unit is
        // already upserted by the time its children are processed.
        foreach ($this->client->getOrganizationalUnits($tenantId, $employerId) as $unit) {
            OrganizationalUnit::query()->updateOrCreate(
                ['id' => $unit['id']],
                [
                    'employer_id' => $employer->id,
                    'parent_id' => $unit['parent_id'],
                    'name' => $unit['name'],
                    'is_legal_entity' => $unit['is_legal_entity'],
                    'kvk_number' => $unit['kvk_number'],
                ],
            );
        }

        foreach ($this->client->getEmployees($tenantId, $employerId) as $employee) {
            Employee::query()->updateOrCreate(
                ['id' => $employee['id']],
                [
                    'employer_id' => $employer->id,
                    'organizational_unit_id' => $employee['organizational_unit_id'],
                    'first_name' => $employee['first_name'],
                    'last_name' => $employee['last_name'],
                    'email' => $employee['email'],
                    'employee_number' => $employee['employee_number'],
                    'status' => $employee['status'],
                ],
            );
        }

        $incoming = $this->client->getContactPersons($tenantId, $employerId);
        $incomingIds = array_column($incoming, 'id');

        ContactPerson::query()->where('employer_id', $employer->id)
            ->whereNotIn('id', $incomingIds)
            ->delete();

        foreach ($incoming as $person) {
            ContactPerson::query()->updateOrCreate(
                ['id' => $person['id']],
                [
                    'employer_id' => $employer->id,
                    'name' => $person['name'],
                    'email' => $person['email'] ?? null,
                    'phone' => $person['phone'] ?? null,
                    'job_title' => $person['job_title'] ?? null,
                ],
            );
        }

        return $employer;
    }
}
