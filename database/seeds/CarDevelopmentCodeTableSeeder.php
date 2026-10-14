<?php

use App\Models\CarDevelopmentCode;
use Illuminate\Database\Seeder;

class CarDevelopmentCodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $series_one = [];
//        $series_two = [];
//        $series_three = [];
//        $series_four = [];
//        $series_five = [];
//        $series_six = [];
//        $series_seven = [];
//        $series_eight = [];
//        $series_I = [];
//        $MINI = [];
//        $Rolls_Royce= [];
//        $series_X = [];
//        $series_Z = [];

        $development_codes = [];


        $series_one = ['E81','E82','E87','E88','F20','F21','F40','F52'];
        $series_two = ['F22','F23','F44','F45','F46'];
        $series_three = ['E90','E91','E92','E93','F30','F31','F34','F35','F80','G20','G21','G28'];
        $series_four = ['F32','F33','F36','F82','F83'];
        $series_five = ['E60','E61','F07','F10','F11','F18','F90','G30','G31','G38'];
        $series_six = ['E63','E64','F06','F12','F13','G32'];
        $series_seven = ['E65','E66','E68','F01','F02','G11','G12'];
        $series_eight = ['F91','F92','F93','G14','G15','G16'];
        $series_I = ['i12','i15'];
        $series_X = ['E53','E70','E71','E83','E84','F15','F16','F25','F26','F39','F48','F49','F85','F86','F95','F96','F97','F98','G01','G02','G06','G07','G08'];
        $series_Z = ['E52','E85','E86','E89','G29'];
        $MINI = ['F54','F55','F56','F57','F60','R55','R56','R57','R58','R59','R60','R61'];
        $Rolls_Royce= ['RR1','RR11','RR12','RR2','RR3','RR31','RR4','RR5','RR6'];
        foreach ($series_one as $one)
        {
            CarDevelopmentCode::create([
                'name' => $one,
                'car_size_id' => 1,
            ]);
        }
        foreach ($series_two as $two)
        {
            CarDevelopmentCode::create([
                'name' => $two,
                'car_size_id' => 2,
            ]);
        }
        foreach ($series_three as $three)
        {
            CarDevelopmentCode::create([
                'name' => $three,
                'car_size_id' => 3,
            ]);
        }
        foreach ($series_four as $four)
        {
            CarDevelopmentCode::create([
                'name' => $four,
                'car_size_id' => 4,
            ]);
        }
        foreach ($series_five as $five)
        {
            CarDevelopmentCode::create([
                'name' => $five,
                'car_size_id' => 5,
            ]);
        }
        foreach ($series_six as $six)
        {
            CarDevelopmentCode::create([
                'name' => $six,
                'car_size_id' => 6,
            ]);
        }
        foreach ($series_seven as $seven)
        {
            CarDevelopmentCode::create([
                'name' => $seven,
                'car_size_id' => 7,
            ]);
        }
        foreach ($series_eight as $eight)
        {
            CarDevelopmentCode::create([
                'name' => $eight,
                'car_size_id' => 8,
            ]);
        }
        foreach ($series_I as $I)
        {
            CarDevelopmentCode::create([
                'name' => $I,
                'car_size_id' => 9,
            ]);
        }
        foreach ($series_X as $X)
        {
            CarDevelopmentCode::create([
                'name' => $X,
                'car_size_id' => 10,
            ]);
        }
        foreach ($series_Z as $Z)
        {
            CarDevelopmentCode::create([
                'name' => $Z,
                'car_size_id' => 11,
            ]);
        }
        foreach ($MINI as $item)
        {
            CarDevelopmentCode::create([
                'name' => $item,
                'car_size_id' => 12,
            ]);
        }
        foreach ($Rolls_Royce as $item)
        {
            CarDevelopmentCode::create([
                'name' => $item,
                'car_size_id' => 13,
            ]);
        }

    }
}
