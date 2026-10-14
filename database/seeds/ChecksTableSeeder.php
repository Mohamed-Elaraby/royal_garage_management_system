<?php

use App\Models\Check;
use Illuminate\Database\Seeder;

class ChecksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Check::class, 10)->create();
    }
}
