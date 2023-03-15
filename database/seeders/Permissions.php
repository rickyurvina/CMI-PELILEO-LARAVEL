<?php

namespace Database\Seeders;

use App\Abstracts\Model;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Permissions extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $rows = [
            'super-admin' => [
                'permissions' => []
            ],
            'admin' => [
                'permissions' => [
                    'manage-roles' => 'Administrar Roles',
                    'manage-users' => 'Administrar Usuarios',
                    'manage-plans' => 'Administrar Planes',
                ]
            ],
            'editor' => [
                'permissions' => [
                    'manage-plans' => 'Administrar Planes',
                ]
            ],
            'authority' => [
                'permissions' => [
                    'view-dashboard' => 'Acceso Panel de Control',
                    'view-plan' => 'Acceso Plan Estratégico',
                ]
            ]
        ];

        $this->attachPermissionsByRoleNames($rows);
    }

    public function attachPermissionsByRoleNames($roles)
    {
        foreach ($roles as $role_name => $role) {
            $roleModel = $this->createRole($role_name);

            foreach ($role['permissions'] as $key => $value) {
                $this->attachPermission($roleModel, $key, $value);
            }
        }
    }

    public function createRole($name)
    {
        return Role::firstOrCreate([
            'name' => $name,
        ]);
    }

    public function attachPermission($role, $permission, $permission_name)
    {
        if (is_string($permission)) {
            $permission = $this->createPermission($permission, $permission_name);
        }

        if ($role->hasPermissionTo($permission->name)) {
            return;
        }

        $role->givePermissionTo($permission);
    }

    public function createPermission($name, $display_name = null)
    {
        $display_name = $display_name ?? Str::title($name);

        return Permission::firstOrCreate([
            'name' => $name,
        ], [
            'display_name' => $display_name
        ]);
    }
}
