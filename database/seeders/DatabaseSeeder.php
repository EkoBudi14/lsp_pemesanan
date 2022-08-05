<?php

namespace Database\Seeders;

use App\Models\category;
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
        \App\Models\User::factory(1)->create();
        category::create([
            'name' => 'Dessert'
        ]);
        category::create([
            'name' => 'Appetizer'
        ]);
        category::create([
            'name' => 'Makanan Utama'
        ]);
    }
}
