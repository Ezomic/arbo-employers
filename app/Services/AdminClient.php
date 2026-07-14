<?php

namespace App\Services;

use RobbinThijssen\IdentitySsoKit\Api\InternalApiClient;

class AdminClient extends InternalApiClient
{
    protected function baseUrl(): string
    {
        return config('services.admin.base_url');
    }

    protected function token(): string
    {
        return config('services.admin.token');
    }

    /** @return list<string> */
    public function getRolePermissions(string $tenantId, string $roleName): array
    {
        return array_values($this->get('role-permissions', [
            'tenant_id' => $tenantId,
            'role_name' => $roleName,
            'app_slug' => 'employers',
        ]));
    }
}
