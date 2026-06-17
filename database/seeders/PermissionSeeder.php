<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminPermissionArray = [
            'Access Dashboard',
        ];

        $adminRolePermissionArray = [
            'Index Role',
            'Create Role',
            'Edit Role',
            'Delete Role',
        ];

        $adminUserPermissionArray = [
            'Index User',
            'Create User',
            'Edit User',
            'Delete User',
        ];

        // Access For Admin DashBoard
        $adminDashboardModule = Module::where('module_name', 'Admin Dashboard')
        ->select('id')
        ->first();

        Permission::updateOrCreate([
            'module_id' => $adminDashboardModule->id,
            'permission_name' => $adminPermissionArray[0],
            'permission_slug' => Str::slug($adminPermissionArray[0])
        ]);

        // Access For Role Management
        $roleManagementModule = Module::where('module_name', 'Role Management')
        ->select('id')
        ->first();

        for ($i=0; $i < count($adminRolePermissionArray) ; $i++) {
            Permission::updateOrCreate([
                'module_id' => $roleManagementModule->id,
                'permission_name' => $adminRolePermissionArray[$i],
                'permission_slug' => Str::slug($adminRolePermissionArray[$i])
            ]);
        }

        // Access For User Management
        $userManagementModule = Module::where('module_name', 'User Management')
        ->select('id')
        ->first();

        for ($i=0; $i < count($adminUserPermissionArray) ; $i++) {
            Permission::updateOrCreate([
                'module_id' => $userManagementModule->id,
                'permission_name' => $adminUserPermissionArray[$i],
                'permission_slug' => Str::slug($adminUserPermissionArray[$i])
            ]);
        }
    }
}
