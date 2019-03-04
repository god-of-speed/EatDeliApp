<?php

use App\Tag;
use App\Domain;
use App\DomainTag;
use Illuminate\Database\Seeder;

class DomainTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate domain_tags table
        DomainTag::truncate();
        $faker = \Faker\Factory::create();
        $domains = Domain::all()->pluck('id')->toArray();
        $tags = Tag::all()->pluck('id')->toArray();

        for($i=0; $i<=3; $i++) {
            DomainTag::create([
                'domain_id' => $faker->randomElement($domains),
                'tag_id' => $faker->randomElement($tags)
            ]);
        }
    }
}
