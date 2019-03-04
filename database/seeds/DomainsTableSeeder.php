<?php

use App\User;
use App\Domain;
use Illuminate\Database\Seeder;

class DomainsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate domain table
        Domain::truncate();
        $faker = \Faker\Factory::create();
        $users = User::all()->pluck('id')->toArray();

        for($i=0; $i<=2; $i++) {
            Domain::create([
                'brandname' => $faker->company,
                'description' => $faker->sentence,
                'brandImage' => $faker->image(public_path().'/images/brands',400,300, null, false) ,
                'user_id' => $faker->randomElement($users)
            ]);
        }
    }
}
