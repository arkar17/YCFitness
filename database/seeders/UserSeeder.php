<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
        $user =User::create([
            'id'=>1,
            'name'=>'King',
            'email' =>'king@gmail.com',
            'phone'=>'09422216317',
            'password' => Hash::make('1382014YC'),
           ]);
        $user->assignRole('King');

        $user =User::create([
            'id'=>2,
            'name'=>'Queen',
            'email' =>'queen@gmail.com',
            'phone'=>'09250320376',
            'password' => Hash::make('1382014YC'),
           ]);
        $user->assignRole('Queen');
        
        $user =User::create([
            'id'=>3,
            'name'=>'user',
            'email' =>'user@gmail.com',
            'phone'=>'0912345678',
            'password' => Hash::make('12345678'),
           ]);
        $user->assignRole('System_Admin');

        $user =User::create([
            'id'=>4,
            'name'=>'admin',
            'email' =>'admin@gmail.com',
            'phone'=>'09123456788',
            'password' => Hash::make('12345678'),
           ]);
        $user->assignRole('Admin');

        $user =User::create([
            'id'=>5,
            'name'=>'trainer',
            'email' =>'trainer@gmail.com',
            'phone'=>'09123456789',
            'password' => Hash::make('12345678'),
           ]);
        $user->assignRole('Trainer');

        $user =User::create([
            'id'=>6,
            'name'=>'ktw',
            'email' =>'ktw@gmail.com',
            'phone'=>'09791867084',
            'member_type' => 'Free',
            'password' => Hash::make('12345678!'),
           ]);

        $user =User::create([
            'id'=>8,
            'name'=>'Default User',
            'email' =>'defaultaccount@gmail.com',
            'phone'=>'09100100100',
            'member_type' => 'Free',
            'password' => Hash::make('12345678!'),
           ]);

        $user =User::create([
            'id'=>10,
            'name'=>'hya',
            'email' =>'hya@gmail.com',
            'phone'=>'09752396518',
            'member_type' => 'Free',
            'password' => Hash::make('12345678'),
           ]);
    }
}
