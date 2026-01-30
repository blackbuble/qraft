<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Run extends Model
{
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
