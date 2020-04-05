<?php

use Kouloughli\Permission;
use Kouloughli\Role;
use Illuminate\Database\Seeder;

class ActivityPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('role_name', 'Admin')->first();

        $permission = Permission::create([
            'name' => 'users.activity',
            'display_name' => 'Afficher le journal d\'activité du système',
            'description' => 'Afficher le journal d\'activité de tous les utilisateurs du système.',
            'removable' => false
        ]);

        $adminRole->attachPermission($permission);
    }
}
