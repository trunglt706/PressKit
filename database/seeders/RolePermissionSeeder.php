<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed default roles and permissions.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'write articles',
            'review content',
            'publish articles',
            'access admin',
        ];

        foreach ($permissions as $permissionName) {
            Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $writerRole = Role::query()->firstOrCreate([
            'name' => 'writer',
            'guard_name' => 'web',
        ]);
        $reviewerRole = Role::query()->firstOrCreate([
            'name' => 'reviewer',
            'guard_name' => 'web',
        ]);
        $publisherRole = Role::query()->firstOrCreate([
            'name' => 'publisher',
            'guard_name' => 'web',
        ]);
        $adminRole = Role::query()->firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $writerRole->syncPermissions(['write articles']);
        $reviewerRole->syncPermissions(['review content']);
        $publisherRole->syncPermissions(['publish articles']);
        $adminRole->syncPermissions($permissions);

        $adminUser = User::query()->where('email', 'test@example.com')->first();
        if ($adminUser) {
            $adminUser->syncRoles(['admin']);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
