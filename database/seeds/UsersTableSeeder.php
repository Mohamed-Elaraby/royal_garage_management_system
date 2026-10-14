<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_owner = User::create([
            'name' => 'Developer',
            'email' => 'developer@skbmw-system.com',
            'password' => bcrypt('123123'),
            'job_title_id' => 5,
            'role_id' => 5,
        ]);
        $user_owner -> attachRole('super_owner');

//        $user_owner = User::create([
//            'name' => 'Amar Ramadan',
//            'email' => 'amarrmdn@hotmail.com',
//            'password' => bcrypt('123123'),
//            'job_title_id' => 1,
//            'role_id' => 1,
//        ]);
//        $user_owner -> attachRole('owner');
//
//        $user_owner = User::create([
//            'name' => 'Mohamed',
//            'email' => 'mido.b333@gmail.com',
//            'password' => bcrypt('123123'),
//            'job_title_id' => 4,
//            'role_id' => 4,
//            'branch_id' => 2,
//        ]);
//        $user_owner -> attachRole('accountant');


    }
}
