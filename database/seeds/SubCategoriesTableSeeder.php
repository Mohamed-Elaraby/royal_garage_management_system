<?php

use App\Models\SubCategories;
use Illuminate\Database\Seeder;

class SubCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sub_categories = ['maintenance', 'electric', 'paints', 'mechanics', 'spare parts'];
        foreach ($sub_categories as $sub_category) {
            SubCategories::create([
                'name' => $sub_category,
                'category_id' => rand(1,4)
            ]);
        }
    }
}
