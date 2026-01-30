<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestFlakiness extends Model
{
    protected $table = 'test_flakiness';

    protected $fillable = [
        'test_scenario_id',
        'flakiness_score',
        'total_runs',
        'pass_count',
        'fail_count',
        'transition_count',
        'pattern',
        'last_analyzed_at',
        'ai_diagnosis',
        'suggested_fix',
    ];

    protected $casts = [
        'pattern' => 'array',
        'last_analyzed_at' => 'datetime',
    ];

    public function testScenario()
    {
        return $this->belongsTo(TestScenario::class);
    }

    /**
     * Get flakiness severity level
     */
    public function getSeverityAttribute(): string
    {
        if ($this->flakiness_score >= 70) {
            return 'critical';
        } elseif ($this->flakiness_score >= 40) {
            return 'warning';
        } else {
            return 'low';
        }
    }

    /**
     * Check if test is considered flaky
     */
    public function isFlaky(): bool
    {
        return $this->flakiness_score > 20;
    }
}
