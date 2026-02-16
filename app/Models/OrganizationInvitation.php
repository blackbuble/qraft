<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OrganizationInvitation extends Model
{
    use HasUuids;

    protected $fillable = [
        'organization_id',
        'email',
        'role',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the organization that owns the invitation.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Check if invitation is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if invitation is valid.
     */
    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Generate a unique invitation token.
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }

    /**
     * Create a new invitation.
     */
    public static function createInvitation(
        Organization $organization,
        string $email,
        string $role = 'member'
    ): self {
        return self::create([
            'organization_id' => $organization->id,
            'email' => $email,
            'role' => $role,
            'token' => self::generateToken(),
            'expires_at' => now()->addDays(7), // 7 days expiry
        ]);
    }

    /**
     * Accept the invitation.
     */
    public function accept(User $user): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        // Add user to organization
        $this->organization->users()->attach($user->id, [
            'role' => $this->role,
        ]);

        // Delete the invitation
        $this->delete();

        return true;
    }
}
