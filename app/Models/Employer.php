<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RobbinThijssen\IdentitySsoKit\Concerns\HasTenantScope;

#[Fillable(['id', 'tenant_id', 'name', 'address_line_1', 'address_line_2', 'postal_code', 'city'])]
class Employer extends Model
{
    use HasTenantScope;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @return HasMany<Contract, $this>
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * @return HasMany<Employee, $this>
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * @return HasMany<OrganizationalUnit, $this>
     */
    public function organizationalUnits(): HasMany
    {
        return $this->hasMany(OrganizationalUnit::class);
    }

    /**
     * @return HasMany<ContactPerson, $this>
     */
    public function contactPersons(): HasMany
    {
        return $this->hasMany(ContactPerson::class);
    }
}
