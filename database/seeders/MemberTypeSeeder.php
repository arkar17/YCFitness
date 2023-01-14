<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Member::create([
        'member_type' =>'Platinum',
        'duration'=>1,
        'price'=>5000,
        'role_id'=>4,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

       Member::create([
        'member_type' =>'Gold',
        'duration'=>1,
        'price'=>20000,
        'role_id'=>5,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

       Member::create([
        'member_type' =>'Diamond',
        'duration'=>1,
        'price'=>40000,
        'role_id'=>6,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

       Member::create([
        'member_type' =>'Ruby',
        'duration'=>1,
        'price'=>100000,
        'role_id'=>7,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

       Member::create([
        'member_type' =>'Ruby Premium',
        'duration'=>1,
        'price'=>200000,
        'role_id'=>8,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

       Member::create([
        'member_type' =>'Gym Member',
        'price'=>40000,
        'duration'=>1,
        'role_id'=>10,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

        Member::create([
        'member_type' =>'Platinum',
        'duration'=>3,
        'price'=>12000,
        'role_id'=>4,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

       Member::create([
        'member_type' =>'Gold',
        'duration'=>3,
        'price'=>50000,
        'role_id'=>5,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

       Member::create([
        'member_type' =>'Diamond',
        'duration'=>3,
        'price'=>100000,
        'role_id'=>6,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

       Member::create([
        'member_type' =>'Ruby',
        'duration'=>3,
        'price'=>250000,
        'role_id'=>7,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

       Member::create([
        'member_type' =>'Ruby Premium',
        'duration'=>3,
        'price'=>500000,
        'role_id'=>8,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

       Member::create([
        'member_type' =>'Gym Member',
        'price'=>100000,
        'duration'=>3,
        'role_id'=>10,
        'pros'=>'adipisicing elit, Dolore fugit hic,ullam cumque',
        'cons'=>'sequi est, quod'
       ]);

    }
}
