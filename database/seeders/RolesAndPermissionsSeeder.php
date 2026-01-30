<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'view_runs',
            'create_scenarios',
            'edit_scenarios',
            'delete_scenarios',
            'manage_users',
            'manage_settings',
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // create roles and assign created permissions

        // QA: Can do everything related to testing, but not system settings or user management
        $role = Role::create(['name' => 'QA']);
        $role->givePermissionTo([
            'view_runs',
            'create_scenarios',
            'edit_scenarios',
            'delete_scenarios',
            'view_reports'
        ]);

        // CTO/Leads: Read-only mostly + triggering runs (if we had a permission for it), getting reports
        $role = Role::create(['name' => 'CTO']);
        $role->givePermissionTo([
            'view_runs',
            'view_reports'
        ]);

        // Admin: Everything
        $role = Role::create(['name' => 'Admin']);
        $role->givePermissionTo(Permission::all());
    }
}
