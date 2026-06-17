<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('role')->truncate();
        // Create an admin role and assign all permission on it

        $adminPermissions = Permission::select('id')->get();

        Role::updateOrCreate([
            'role_name' => 'Super Admin',
            'role_slug' => 'super_admin',
            'role_note' => 'super admin has all permission',
            'is_deletable' => false,
        ])->permissions()->sync($adminPermissions->pluck('id'));

        Role::updateOrCreate([
            'role_name' => 'Admin',
            'role_slug' => 'admin',
            'role_note' => 'admin has limited permission',
            'is_deletable' => true,
        ]);

        Role::updateOrCreate([
            'role_name' => 'Moderator',
            'role_slug' => 'moderator',
            'role_note' => 'moderator has limited permission',
            'is_deletable' => true,
        ]);

        Role::updateOrCreate([
            'role_name' => 'Customer',
            'role_slug' => 'customer',
            'role_note' => 'customer has limited permission',
            'is_deletable' => true,
        ]);
    }
}
