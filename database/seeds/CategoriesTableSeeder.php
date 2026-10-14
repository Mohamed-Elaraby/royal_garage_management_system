<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $main_categories = ['BMW', 'volkswagen', 'Maserati', 'Audi'];
        foreach ($main_categories as $main_category) {
            Category::create([
                'name' => $main_category
            ]);
        }
    }
}
