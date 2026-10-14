<?php

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 7; $i++)
        {
            Branch::create([
                'name' => 'الفرع_'.$i,
                'display_name' => 'الفرع_'.$i,
                'phone' => random_int(100000,1000000),
            ]);
        }
    }
}
