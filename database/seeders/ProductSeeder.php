<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=ProductSeeder
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();

        Product::create([
            'user_id' => 1,
            'name' => 'Seeder test',
            'price' => 10,
            'quantity' => 100,
            'description' => 'Description',
        ]);
    }
}
