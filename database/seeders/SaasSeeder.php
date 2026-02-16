<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SaasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo users
        $user1 = User::firstOrCreate(
            ['email' => 'owner@qraft.test'],
            [
                'name' => 'Demo Owner',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'member@qraft.test'],
            [
                'name' => 'Demo Member',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create demo organizations
        $org1 = Organization::firstOrCreate(
            ['slug' => 'acme'],
            [
                'name' => 'Acme Corporation',
                'owner_id' => $user1->id,
                'subscription_plan' => 'pro',
                'subscription_status' => 'active',
                'trial_ends_at' => now()->addDays(14),
            ]
        );

        $org2 = Organization::firstOrCreate(
            ['slug' => 'tech-startup'],
            [
                'name' => 'Tech Startup',
                'owner_id' => $user2->id,
                'subscription_plan' => 'free',
                'subscription_status' => 'active',
                'trial_ends_at' => now()->addDays(14),
            ]
        );

        // Attach users to organizations (sync to avoid duplicates)
        $org1->users()->syncWithoutDetaching([
            $user1->id => ['role' => 'owner'],
            $user2->id => ['role' => 'member']
        ]);

        $org2->users()->syncWithoutDetaching([
            $user2->id => ['role' => 'owner']
        ]);

        $this->command->info('✅ Created/Updated 2 demo users and 2 organizations');
        $this->command->info('   - owner@qraft.test / password (Acme Corporation - Pro plan)');
        $this->command->info('   - member@qraft.test / password (Tech Startup - Free plan)');
    }
}
