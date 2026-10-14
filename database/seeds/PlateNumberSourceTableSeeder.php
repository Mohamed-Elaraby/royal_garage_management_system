<?php

use App\Models\PlateNumberSource;
use Illuminate\Database\Seeder;

class PlateNumberSourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            'ابو ظبى', // id=1
            'دبي', // id=2
            'الشارقة', // id=3
            'عجمان', // id=4
            'ام القيوين', // id=5
            'راس الخيمة', // id=6
            'الفجيرة' // id=7
        ];
        foreach ($countries as $country)
        {
            PlateNumberSource::create([
                'name' => $country
            ]);
        }
    }
}
