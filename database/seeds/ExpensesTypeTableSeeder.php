<?php

use App\Models\ExpensesType;
use Illuminate\Database\Seeder;

class ExpensesTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['كهرباء', 'بنزين', 'فطار', 'ايجار'];
        foreach ($types as $type) {
            ExpensesType::create([
                'name' => $type,
            ]);
        }
    }
}
