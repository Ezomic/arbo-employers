<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id', 'employer_id', 'name', 'email', 'phone', 'job_title'])]
class ContactPerson extends Model
{
    protected $table = 'contact_persons';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @return BelongsTo<Employer, $this>
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }
}
