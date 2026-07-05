<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use RobbinThijssen\IdentitySsoKit\Api\InternalApiClient;

/**
 * Case Officers owns Employer/Contract/Employee master data — this app only
 * ever writes those through its internal API, then caches the result
 * locally so its own pages don't need a live call on every read.
 */
class CaseOfficersClient extends InternalApiClient
{
    protected function baseUrl(): string
    {
        return config('services.case_officers.base_url');
    }

    protected function token(): string
    {
        return config('services.case_officers.token');
    }

    /**
     * @return array<string, mixed>
     */
    public function getEmployer(string $tenantId, string $employerId): array
    {
        return $this->get("employers/{$employerId}", ['tenant_id' => $tenantId]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getContracts(string $tenantId, string $employerId): array
    {
        return $this->get("employers/{$employerId}/contracts", ['tenant_id' => $tenantId]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEmployees(string $tenantId, string $employerId): array
    {
        return $this->get("employers/{$employerId}/employees", ['tenant_id' => $tenantId]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getOrganizationalUnits(string $tenantId, string $employerId): array
    {
        return $this->get("employers/{$employerId}/organizational-units", ['tenant_id' => $tenantId]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getContactPersons(string $tenantId, string $employerId): array
    {
        return $this->get("employers/{$employerId}/contact-persons", ['tenant_id' => $tenantId]);
    }

    /**
     * @param  array<string, mixed>  $employee
     * @return array<string, mixed>
     */
    public function createEmployee(string $tenantId, string $employerId, array $employee): array
    {
        return $this->post("employers/{$employerId}/employees", [
            'tenant_id' => $tenantId,
            ...$employee,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function importEmployees(string $tenantId, string $employerId, string $userId, UploadedFile $file): array
    {
        return $this->postFile("employers/{$employerId}/employees/import", $file, [
            'tenant_id' => $tenantId,
            'initiated_by_user_id' => $userId,
            'initiated_by_app' => 'employers',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function getImportStatus(string $tenantId, int $importId): array
    {
        return $this->get("employee-imports/{$importId}", ['tenant_id' => $tenantId]);
    }

    /**
     * Registers the start of an absence course for one of this employer's
     * own employees — this is what actually creates the case on Case
     * Officers' side.
     *
     * @return array<string, mixed>
     */
    public function createCase(string $tenantId, string $employeeId, string $startDate): array
    {
        return $this->post('cases', [
            'tenant_id' => $tenantId,
            'employee_id' => $employeeId,
            'start_date' => $startDate,
        ]);
    }
}
