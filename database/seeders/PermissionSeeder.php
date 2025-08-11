<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission['Admin'] = [
            'dashboard.view',
            'products.view',
            'orders.view',
            'settings.view',
        ];
        $permission['User'] = [];

        foreach ($permission as $key => $value) {
            foreach ($value as $p) {
                Permission::findOrCreate($p, 'web');
            }
            $role = Role::findOrCreate($key, 'web');
            $role->syncPermissions($value);
        }

        $role = Role::findOrCreate('Admin', 'web');

        foreach (array_keys($permission) as $key) {
            $permission['Admin'] = array_merge($permission['Admin'], $permission[$key]);
        }

        $role->syncPermissions($permission['Admin']);

        // create Admin role and sync permissions
        $user = User::updateOrCreate(
            ['email' => 'tokounyil@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'), // Use a secure password in production
            ]
        );
        $user->assignRole($role);

        $user = User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User',
                'password' => bcrypt('password'), // Use a secure password in production
            ]
        );
        $user->assignRole('User');

        // query all user that havent have role than assign them to User role
        $usersWithoutRole = User::doesntHave('roles')->get();
        foreach ($usersWithoutRole as $user) {
            $user->assignRole('User');
        }

        Artisan::call('cache:clear');
    }
}
