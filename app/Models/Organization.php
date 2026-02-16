<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;

class Organization extends Model
{
    use HasFactory, HasUuids, Billable;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'subscription_plan',
        'subscription_status',
        'trial_ends_at',
        'settings',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'settings' => 'array',
    ];

    /**
     * Get the owner of the organization.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all users belonging to this organization.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get all projects belonging to this organization.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get all agents belonging to this organization.
     */
    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }

    /**
     * Get all test scenarios belonging to this organization.
     */
    public function testScenarios(): HasMany
    {
        return $this->hasMany(TestScenario::class);
    }

    /**
     * Get all runs belonging to this organization.
     */
    public function runs(): HasMany
    {
        return $this->hasMany(Run::class);
    }

    /**
     * Get subscription plan limits.
     */
    public function subscriptionPlans(): array
    {
        return [
            'free' => [
                'name' => 'Free',
                'price' => 0,
                'limits' => [
                    'projects' => 1,
                    'test_runs_per_month' => 100,
                    'team_members' => 3,
                    'ai_generations_per_month' => 10,
                    'storage_gb' => 1,
                ],
            ],
            'pro' => [
                'name' => 'Pro',
                'price' => 49,
                'stripe_price_id' => env('STRIPE_PRICE_PRO'),
                'limits' => [
                    'projects' => 10,
                    'test_runs_per_month' => 5000,
                    'team_members' => 10,
                    'ai_generations_per_month' => 500,
                    'storage_gb' => 50,
                ],
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 299,
                'stripe_price_id' => env('STRIPE_PRICE_ENTERPRISE'),
                'limits' => [
                    'projects' => -1, // unlimited
                    'test_runs_per_month' => -1,
                    'team_members' => -1,
                    'ai_generations_per_month' => -1,
                    'storage_gb' => -1,
                ],
            ],
        ];
    }

    /**
     * Get the limit for a specific feature based on current plan.
     */
    public function planLimit(string $feature): int
    {
        $plans = $this->subscriptionPlans();
        $currentPlan = $this->subscription_plan ?? 'free';

        return $plans[$currentPlan]['limits'][$feature] ?? 0;
    }

    /**
     * Check if organization is on trial.
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if organization has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === 'active' || $this->onTrial();
    }

    /**
     * Get the current plan name.
     */
    public function currentPlanName(): string
    {
        $plans = $this->subscriptionPlans();
        return $plans[$this->subscription_plan]['name'] ?? 'Free';
    }
}
