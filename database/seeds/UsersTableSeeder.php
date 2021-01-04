<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'terminal' => 1,
        ]);

        $cs = User::create([
            'name' => 'Customer Service 1',
            'username' => 'cs1',
            'email' => 'cs1@admin.com',
            'password' => Hash::make('admin123'),
            'terminal' => 2,
        ]);

        $admin->roles()->attach(1);
        $cs->roles()->attach(2);
    }
}
