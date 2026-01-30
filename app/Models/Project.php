<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    protected $casts = [
        'notification_emails' => 'array',
    ];

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    public function runs()
    {
        return $this->hasMany(Run::class);
    }

    public function testScenarios()
    {
        return $this->hasMany(TestScenario::class);
    }
}
