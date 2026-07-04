<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id', 'tenant_id', 'employee_id', 'case_type', 'status', 'opened_at', 'expected_return_date', 'closed_at'])]
class CaseFile extends Model
{
    public $incrementing = false;

    protected $table = 'cases';

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'opened_at' => 'date',
            'expected_return_date' => 'date',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Employee, $this>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
