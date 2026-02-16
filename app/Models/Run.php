<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;

class Run extends Model
{
    use BelongsToOrganization;

    protected $guarded = [];
    protected $casts = [
        'result' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'severity' => 'string',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function testScenario()
    {
        return $this->belongsTo(TestScenario::class);
    }
}
