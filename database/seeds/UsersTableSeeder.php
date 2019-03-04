<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        //clear the users table
        User::truncate();
        $password = Hash::make(1234567);
        $roles = Role::all()->pluck('id')->toArray();

        User::create([
            'firstname' => 'ebuka',
            'lastname' =>  'ubah',
            'username' => 'godofspeed',
            'password' => $password,
            'role_id' => 1
        ]);

        for($i=0; $i<12; $i++) {
            User::create([
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'username' => $faker->userName,
                'password' => $password,
                'role_id' => $faker->randomElement($roles)
            ]);
        }
    }
}
