<?php

use App\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate tag table
        Tag::truncate();
        $faker = \Faker\Factory::create();

        Tag::create([
            'tag' => 'cake'
        ]);

        for($i=0; $i<12; $i++) {
            Tag::create([
                'tag' => $faker->word
            ]);
        }
    }
}
