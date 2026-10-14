<?php

use App\Models\Nationality;
use Illuminate\Database\Seeder;

class NationalitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nationalities = ['مصرى', 'باكستانى', 'هندى', 'سورى', 'فلبينى', 'سعودى'];
        foreach ($nationalities as $nationality) {
            Nationality::create(['nationality' => $nationality]);
        }
    }
}
