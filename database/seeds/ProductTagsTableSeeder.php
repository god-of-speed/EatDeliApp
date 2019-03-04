<?php

use App\Tag;
use App\Product;
use App\ProductTag;
use Illuminate\Database\Seeder;

class ProductTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate product_tags table
        ProductTag::truncate();
        $faker = \Faker\Factory::create();
        $tags = Tag::all()->pluck('id')->toArray();
        $products = Product::all()->pluck('id')->toArray();

        for($i=0; $i<=3; $i++) {
            ProductTag::create([
                'product_id' => $faker->randomElement($products),
                'tag_id' => $faker->randomElement($tags)
            ]);
        }
    }
}
