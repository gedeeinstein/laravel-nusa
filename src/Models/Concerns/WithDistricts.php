<?php

declare(strict_types=1);

namespace Creasi\Nusa\Models\Concerns;

use Creasi\Nusa\Models\District;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Creasi\Nusa\Contracts\District> $districts
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait WithDistricts
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Creasi\Nusa\Contracts\District
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
