<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
        'name' =>'King',
        'guard_name' => 'web'
        ]);

        Role::create([
            'name' =>'Admin',
            'guard_name' => 'web'
            ]);

        Role::create([
        'name' =>'Queen',
        'guard_name' => 'web'
        ]);

        Role::create([
        'name' =>'System_Admin',
        'guard_name' => 'web'
        ]);

        Role::create([
            'name' =>'Platinum',
            'guard_name' => 'web'
            ]);

        Role::create([
        'name' =>'Gold',
        'guard_name' => 'web'
        ]);

        Role::create([
            'name' =>'Diamond',
            'guard_name' => 'web'
            ]);

        Role::create([
            'name' =>'Ruby',
            'guard_name' => 'web'
            ]);

        Role::create([
            'name' =>'Ruby Premium',
            'guard_name' => 'web'
            ]);

        Role::create([
            'name' =>'Trainer',
            'guard_name' => 'web'
            ]);

        Role::create([
            'name' =>'Gym Member',
            'guard_name' => 'web'
            ]);

    }
}
