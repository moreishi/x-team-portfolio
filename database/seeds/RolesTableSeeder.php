<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Admin',
            'slug' => Str::slug('admin')
        ]);

        Role::create([
            'name' => 'Regular',
            'slug' => Str::slug('regular')
        ]);

        Role::create([
            'name' => 'Hiring Manager',
            'slug' => Str::slug('hiring-manager')
        ]);
    }
}
