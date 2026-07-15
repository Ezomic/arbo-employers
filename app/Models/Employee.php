<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $employer_id
 * @property string|null $organizational_unit_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $email
 * @property string|null $employee_number
 * @property string|null $status
 * @property Carbon|null $date_of_birth
 * @property string|null $gender
 * @property string|null $nationality
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['id', 'employer_id', 'organizational_unit_id', 'first_name', 'last_name', 'email', 'employee_number', 'status', 'date_of_birth', 'gender', 'nationality'])]
class Employee extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Employer, $this>
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    /**
     * @return BelongsTo<OrganizationalUnit, $this>
     */
    public function organizationalUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    /**
     * @return MorphOne<Address, $this>
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
