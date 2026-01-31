<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    /**
     * Get all organizations this user belongs to.
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the current organization for this user.
     */
    public function currentOrganization(): ?Organization
    {
        // Get from session or return first organization
        $orgId = session('current_organization_id');

        if ($orgId) {
            return $this->organizations()->find($orgId);
        }

        return $this->organizations()->first();
    }

    /**
     * Switch to a different organization.
     */
    public function switchOrganization(string $organizationId): bool
    {
        if ($this->organizations()->where('organizations.id', $organizationId)->exists()) {
            session(['current_organization_id' => $organizationId]);
            return true;
        }

        return false;
    }

    /**
     * Check if user owns the given organization.
     */
    public function ownsOrganization(Organization $organization): bool
    {
        return $this->id === $organization->owner_id;
    }

    /**
     * Get user's role in the given organization.
     */
    public function roleInOrganization(Organization $organization): ?string
    {
        $pivot = $this->organizations()->where('organizations.id', $organization->id)->first()?->pivot;
        return $pivot?->role;
    }

    /**
     * Get monthly test run count
     */
    public function getMonthlyRunCount(): int
    {
        return \App\Models\Run::whereHas('testScenario.project', function ($query) {
            $query->where('user_id', $this->id);
        })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    /**
     * Get monthly run limit based on plan
     */
    public function getMonthlyRunLimit(): int
    {
        // TODO: Implement plan-based limits
        // For now, return free tier limit
        return 100;
    }

    /**
     * Check if user has reached usage limit
     */
    public function hasReachedUsageLimit(): bool
    {
        return $this->getMonthlyRunCount() >= $this->getMonthlyRunLimit();
    }

    /**
     * Get usage percentage
     */
    public function getUsagePercentage(): float
    {
        $limit = $this->getMonthlyRunLimit();
        if ($limit === 0) {
            return 0;
        }
        return ($this->getMonthlyRunCount() / $limit) * 100;
    }

    /**
     * Check and send usage notifications
     */
    public function checkUsageLimitAndNotify(): void
    {
        $currentRuns = $this->getMonthlyRunCount();
        $limit = $this->getMonthlyRunLimit();
        $percentage = $this->getUsagePercentage();

        // Send notification at 80%, 95%, and 100%
        if ($percentage >= 80) {
            \App\Notifications\UsageLimitNotification::sendFilamentNotification(
                $this,
                $currentRuns,
                $limit
            );
        }
    }
}
