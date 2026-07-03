<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id', 'employer_id', 'parent_id', 'name', 'is_legal_entity', 'kvk_number'])]
class OrganizationalUnit extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'is_legal_entity' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Employer, $this>
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }
}
