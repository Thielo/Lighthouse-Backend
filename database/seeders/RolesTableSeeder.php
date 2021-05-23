<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        $permissions = [
            'view_threads',
            'create_posts',
            'create_threads',
            'manage_users',
            'manage_permissions',
            'manage_roles',
            'manage_threads',
            'manage_posts'
        ];

        $roles = [
            ['admin', 'all'],
            ['moderator', ['manage_threads', 'manage_posts', 'view_threads', 'create_posts', 'create_threads']],
            ['user', ['view_threads', 'create_posts', 'create_threads']],
            ['banned'],
        ];

        $userRoles = [
            'admin' => 'admin',
            'user' => 'user',
            'moderator' => 'moderator',
        ];

        foreach ($permissions as $permission) {
            $permission = Permission::create(['name' => $permission]);
        }

        foreach ($roles as $role) {
            $thisRole = Role::create(['name' => $role[0]]);
            if (isset($role[1])) {
                if (is_array($role[1])) {
                    $thisRole->givePermissionTo($role[1]);
                } elseif ($role[1] == 'all') {
                    $thisRole->givePermissionTo(Permission::all());
                }
            }
        }

        foreach ($userRoles as $userName => $userRole) {
            $user = User::where('username', $userRole)->first();
            $user->assignRole($userRole);
        }

    }

}
