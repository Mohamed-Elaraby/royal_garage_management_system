<?php

use App\Models\Engineer;
use Illuminate\Database\Seeder;

class EngineersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i =1; $i<6 ;$i++) {
            Engineer::create([
                'name' => 'Engineer_'.$i,
                'branch_id' => mt_rand(1,5),
            ]);
        }
    }
}
