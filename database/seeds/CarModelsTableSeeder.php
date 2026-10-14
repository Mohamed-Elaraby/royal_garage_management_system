<?php

use App\Models\CarModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CarModelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentYear = Carbon::now()->year;
        for ($year = 1990; $year <= $currentYear; $year++)
        {
            CarModel::create([
                'name' => $year,
            ]);
        }
    }
}
