<?php

use App\Permission;
use App\Role;
use App\Teams\Roles;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Roles::$roles;

        foreach ($roles as $role => $data) {
            $role = Role::firstOrCreate(['name' => $role], [
                'display_name' => $data['name']
            ]);

            foreach ($data['permissions'] as $permission) {
                Permission::firstOrCreate(['name' => $permission]);

                $role->attachPermission($permission);
            }
        }
    }
}
