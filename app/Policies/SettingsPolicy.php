<?php

namespace App\Policies;

use App\Models\User;

class SettingsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    public function manage(User $user): bool
    {
        return $user->hasRole('Admin');
    }
}
