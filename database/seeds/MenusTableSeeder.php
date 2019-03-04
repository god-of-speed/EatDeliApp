<?php

use App\Menu;
use App\Domain;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //trucate menu
        Menu::truncate();
        $faker = \Faker\Factory::create();
        $domains = Domain::all()->pluck('id')->toArray();

        for($i=0; $i<=3; $i++) {
            Menu::create([
                'title' => $faker->word,
                'description' => $faker->sentence,
                'domain_id' => $faker->randomElement($domains)
            ]);
        }
    }
}
