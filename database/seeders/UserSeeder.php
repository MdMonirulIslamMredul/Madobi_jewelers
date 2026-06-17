<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->truncate();
        //Create Super Admin
        $superAdminRoleId = Role::where('role_slug', 'super_admin')->first()->id;
        User::updateOrCreate([
            'role_id' => $superAdminRoleId,
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);

        //Create Admin
        $adminRoleId = Role::where('role_slug', 'admin')->first()->id;
        User::updateOrCreate([
            'role_id' => $adminRoleId,
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);

        //Create Moderator
        $moderatorRoleId = Role::where('role_slug', 'moderator')->first()->id;
        User::updateOrCreate([
            'role_id' => $moderatorRoleId,
            'name' => 'User X',
            'email' => 'userx@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);
        User::updateOrCreate([
            'role_id' => $moderatorRoleId,
            'name' => 'User Y',
            'email' => 'usery@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);
    }
}
