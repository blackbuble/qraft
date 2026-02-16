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
        $user1 = User::create([
            'name' => 'Demo Owner',
            'email' => 'owner@qraft.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Demo Member',
            'email' => 'member@qraft.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create demo organizations
        $org1 = Organization::create([
            'name' => 'Acme Corporation',
            'slug' => 'acme',
            'owner_id' => $user1->id,
            'subscription_plan' => 'pro',
            'subscription_status' => 'active',
            'trial_ends_at' => now()->addDays(14),
        ]);

        $org2 = Organization::create([
            'name' => 'Tech Startup',
            'slug' => 'tech-startup',
            'owner_id' => $user2->id,
            'subscription_plan' => 'free',
            'subscription_status' => 'active',
            'trial_ends_at' => now()->addDays(14),
        ]);

        // Attach users to organizations
        $org1->users()->attach($user1->id, ['role' => 'owner']);
        $org1->users()->attach($user2->id, ['role' => 'member']);

        $org2->users()->attach($user2->id, ['role' => 'owner']);

        $this->command->info('✅ Created 2 demo users and 2 organizations');
        $this->command->info('   - owner@qraft.test / password (Acme Corporation - Pro plan)');
        $this->command->info('   - member@qraft.test / password (Tech Startup - Free plan)');
    }
}
