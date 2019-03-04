<?php

use App\Menu;
use App\Domain;
use App\MenuClass;
use Illuminate\Database\Seeder;

class Menu_ClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate menu class table
        MenuClass::truncate();
        $faker = \Faker\Factory::create();
        $domains = Domain::all()->pluck('id')->toArray();
        $menus = Menu::all()->pluck('id')->toArray();

        for($i=0; $i<=3; $i++) {
            MenuClass::create([
                'title' => $faker->word,
                'description' => $faker->sentence,
                'menu_id' => $faker->randomElement($menus),
                'domain_id' => $faker->randomElement($domains)
            ]);
        }
    }
}
