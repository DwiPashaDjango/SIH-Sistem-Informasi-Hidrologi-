<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::find(1);
        $admin->assignRole('admin');

        $otherUsers = User::where('id', '!=', 1)->get();
        foreach ($otherUsers as $user) {
            $user->assignRole('User');
        }
    }
}
