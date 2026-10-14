<?php

use App\Models\Technical;
use Illuminate\Database\Seeder;

class TechnicalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i =1; $i <= 10 ;$i++) {
            Technical::create([
                'name' => 'الفنى _ '.$i,
                'branch_id' => 1,
            ]);
        }
    }
}
