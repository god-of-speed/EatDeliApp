<?php

use App\Menu;
use App\Domain;
use App\Product;
use App\MenuClass;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate product table
        Product::truncate();
        $faker = \Faker\Factory::create();
        $menus = Menu::all()->pluck('id')->toArray();
        $domains = Domain::all()->pluck('id')->toArray();
        $menuClasses = MenuClass::all()->pluck('id')->toArray();

        for($i=0; $i<=2; $i++) {
            Product::create([
                'name' => $faker->word,
                'image' => $faker->image(public_path().'/images/products',400,300, null, false),
                'description' => $faker->sentence,
                'oldPrice' => $faker->numberBetween($min=100,$max=80000),
                'price' => $faker->numberBetween($min=100,$max=80000),
                'currency' => 'naira',
                'isset' => $i % 2 == 0 ? true : false,
                'domain_id' => $faker->randomElement($domains),
                'menu_id' => $faker->randomElement($menus),
                'menuClass_id' => $faker->randomElement($menuClasses)
            ]);
        }
    }
}
