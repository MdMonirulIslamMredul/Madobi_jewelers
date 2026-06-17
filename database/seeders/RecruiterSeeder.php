<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Recruiter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RecruiterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Recruiter::create([
            'name' => 'Saurav',
            'email' => 'saurav8@gmail.com',
            'designation' => 'Web Developer',
            'phone' => '01992224698',
            'company_name' => 'TechWeb Bd IT',
            'password' => Hash::make(12345678),
        ]);
    }
}
