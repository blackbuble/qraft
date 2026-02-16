<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;

class TestScenario extends Model
{
    use BelongsToOrganization;
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'steps',
        'network_mocks',
        'priority',
        'is_active',
        'frequency',
        'last_run_at',
        'next_run_at',
    ];

    protected $casts = [
        'steps' => 'array',
        'network_mocks' => 'array',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function runs()
    {
        return $this->hasMany(Run::class);
    }
}
