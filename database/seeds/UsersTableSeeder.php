<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = factory(User::class)->create();
        $hiring_manager = factory(User::class, 5)->create();
        $users = factory(User::class, 50)->create();

        /* Assign Admin role */
        $admin->roles()->attach(Role::where('slug','admin')->first());

        /* Assign Manager Role*/
        foreach($hiring_manager as $user)
        {
            $user->roles()->attach(Role::where('slug','hiring-manager')->first());
        }

        /* Add user role */
        foreach($users as $user)
        {
            $user->roles()->attach(Role::where('slug','regular')->first());
        }
    }
}
