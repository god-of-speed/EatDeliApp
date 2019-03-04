<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate roles
        Role::truncate();
        $faker = \Faker\Factory::create();

        Role::create([
            'title' => 'Role_User',
            'slug' => 'user'
        ]);
        
        for($i=0; $i<=3; $i++) {
            Role::create([
                'title' => $faker->word,
                'slug' => $faker->word
            ]);
        }
    }
}
