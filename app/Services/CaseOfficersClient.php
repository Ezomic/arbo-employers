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

    public function getEmployer(string $tenantId, string $employerId): array
    {
        return $this->get("employers/{$employerId}", ['tenant_id' => $tenantId]);
    }

    public function getContracts(string $tenantId, string $employerId): array
    {
        return $this->get("employers/{$employerId}/contracts", ['tenant_id' => $tenantId]);
    }

    public function getEmployees(string $tenantId, string $employerId): array
    {
        return $this->get("employers/{$employerId}/employees", ['tenant_id' => $tenantId]);
    }

    public function getOrganizationalUnits(string $tenantId, string $employerId): array
    {
        return $this->get("employers/{$employerId}/organizational-units", ['tenant_id' => $tenantId]);
    }

    public function createEmployee(string $tenantId, string $employerId, array $employee): array
    {
        return $this->post("employers/{$employerId}/employees", [
            'tenant_id' => $tenantId,
            ...$employee,
        ]);
    }

    public function importEmployees(string $tenantId, string $employerId, string $userId, UploadedFile $file): array
    {
        return $this->postFile("employers/{$employerId}/employees/import", $file, [
            'tenant_id' => $tenantId,
            'initiated_by_user_id' => $userId,
            'initiated_by_app' => 'employers',
        ]);
    }

    public function getImportStatus(string $tenantId, int $importId): array
    {
        return $this->get("employee-imports/{$importId}", ['tenant_id' => $tenantId]);
    }

    /**
     * Registers the start of an absence course for one of this employer's
     * own employees — this is what actually creates the case on Case
     * Officers' side.
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
