<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(DomainsTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(DomainTagsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(Menu_ClassesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(ProductTagsTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
    }
}
