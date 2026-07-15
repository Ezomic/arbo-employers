<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Rows here are updateOrCreate'd keyed on Case Officers' Address.id, the
 * same pattern Employee already uses. addressable() is never actually
 * resolved in this app (access is always Employee->address) — it works
 * because both apps share the App\Models\Employee FQCN, but don't rely
 * on it beyond that.
 *
 * @property string $id
 * @property string $addressable_type
 * @property string $addressable_id
 * @property string $address_line_1
 * @property string|null $address_line_2
 * @property string $postal_code
 * @property string $city
 * @property string $country
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['id', 'addressable_type', 'addressable_id', 'address_line_1', 'address_line_2', 'postal_code', 'city', 'country'])]
class Address extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @return MorphTo<Model, $this>
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
