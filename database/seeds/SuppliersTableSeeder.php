<?php

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['mohamed', 'ahmed', 'osama'];
        foreach ($names as $name)
        {
            Supplier::create([
                'name' => $name,
                'phone' => '010101011111',
                'vat_number' => '100583112000847000004'
            ]);
        }
    }
}
