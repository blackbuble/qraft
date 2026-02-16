<?php

namespace App\Models\Concerns;

use App\Models\Organization;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOrganization
{
    /**
     * Boot the trait.
     */
    protected static function bootBelongsToOrganization(): void
    {
        // Automatically set organization_id when creating
        static::creating(function ($model) {
            if (!$model->organization_id && Filament::getTenant()) {
                $model->organization_id = Filament::getTenant()->id;
            }
        });

        // Add global scope to filter by current organization
        static::addGlobalScope('organization', function (Builder $builder) {
            if (Filament::getTenant()) {
                $builder->where($builder->getQuery()->from . '.organization_id', Filament::getTenant()->id);
            }
        });
    }

    /**
     * Get the organization that owns the model.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
