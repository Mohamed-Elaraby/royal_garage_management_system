<?php

use App\Models\CarType;
use Illuminate\Database\Seeder;

class CarTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carTypes = [
            'BMW', // id=1
            'MINI', // id=2
            'Rolls-Royce' // id=3
        ];
        foreach ($carTypes as $car)
        {
            CarType::create([
                'name' => $car
            ]);
        }
    }
}
