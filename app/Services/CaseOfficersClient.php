<?php

namespace App\Services;

use App\Models\CaseFile;
use Illuminate\Http\UploadedFile;
use RobbinThijssen\IdentitySsoKit\Api\InternalApiClient;

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

    public function syncCase(CaseFile $case): void
    {
        $this->put("cases/{$case->id}", [
            'tenant_id' => $case->tenant_id,
            'employee_id' => $case->employee_id,
            'status' => $case->status,
            'opened_at' => $case->opened_at->toDateString(),
            'expected_return_date' => $case->expected_return_date?->toDateString(),
            'closed_at' => $case->closed_at?->toDateString(),
        ]);
    }
}
