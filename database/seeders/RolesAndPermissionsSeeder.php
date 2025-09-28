<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define base permissions
        $permissions = [
            'view dashboard',
            'manage devices',
            'manage schedules',
            'manage holidays',
            'manage overtime',
            'manage reports',
            'manage offices',
            'manage areas',
            'manage user offices',
            'manage users',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm);
        }

        // Roles
        $superAdmin = Role::findOrCreate('Super Admin');
        $admin = Role::findOrCreate('Admin');
        $viewer = Role::findOrCreate('Viewer');

        // Super Admin gets all permissions
        $superAdmin->syncPermissions(Permission::all());

        // Admin gets management except user management
        $adminPerms = array_diff($permissions, ['manage users']);
        $admin->syncPermissions(Permission::whereIn('name', $adminPerms)->get());

        // Viewer basic view-only
        $viewer->syncPermissions(Permission::whereIn('name', ['view dashboard', 'manage reports'])->get());

        // Assign first user as Super Admin if exists
        $firstUser = User::query()->orderBy('id')->first();
        if ($firstUser && !$firstUser->hasRole('Super Admin')) {
            $firstUser->assignRole('Super Admin');
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
