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
        // Se il ruolo o il permesso non esistono li crea altrimenti li riusa
        $role_admin = Role::firstOrCreate(['name' => 'admin']);
        $permission_import_file = Permission::firstOrCreate(['name' => 'import files']);

        // Associa all'admin il permesso di importare
        $role_admin->syncPermissions([$permission_import_file]);

        // Assegna il ruolo al primo
        $user = User::find(1);
        if ($user) {
            $user->assignRole($role_admin);
        }

        // Svuota la chache per efficienza
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
