<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $role_admin = Role::firstOrCreate(['name' => 'admin']);
        $permission_import_file = Permission::firstOrCreate(['name' => 'import files']);
        $role_admin->syncPermissions([$permission_import_file]);


        $user = User::find(1);
        if ($user) {
            $user->assignRole($role_admin);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
    }
}
