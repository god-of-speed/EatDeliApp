<?php

use App\User;
use App\Order;
use App\Domain;
use App\Product;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate order table
        Order::truncate();
        $faker = \Faker\Factory::create();
        $buyers = User::all()->pluck('id')->toArray();
        $products = Product::all()->pluck('id')->toArray();
        $domains = Domain::all()->pluck('id')->toArray();

        for($i=0; $i<=3; $i++) {
            Order::create([
                'domain_id' => $faker->randomElement($domains),
                'product_id' => $faker->randomElement($products),
                'buyer_id' => $faker->randomElement($buyers),
                'status' => $faker->word,
                'paid' => $i % 2 ? true : false,
                'delivered' => $i % 2 ? true : false,
                'received' => $i % 2 ? false : true,
                'quantity' => $i,
                'price' => $faker->NumberBetween($min=100,$max=89000)
            ]);
        }
    }
}
