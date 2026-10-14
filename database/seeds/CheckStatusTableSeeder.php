<?php

use App\Models\CheckStatus;
use Illuminate\Database\Seeder;

class CheckStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checkStatus =
            [
                [
                    'name' => 'تحت الفحص',
                    'color' => '#f0ad4e'
                ],
                [
                    'name' => 'انتظار رد العميل',
                    'color' => '#f64833'
                ],
                [
                    'name' => 'تم التعميد من قبل العميل',
                    'color' => '#5bc0de'
                ],
                [
                    'name' => 'السيارة قيد الصيانة',
                    'color' => '#0275d8'
                ],
                [
                    'name' => 'تم الانتهاء من الصيانة',
                    'color' => '#5cb85c'
                ],
                [
                    'name' => 'تم تسليم السيارة الى العميل',
                    'color' => '#d9534f'
                ],
                /*'تحت الفحص',
                'انتظار رد العميل',
                'تم التعميد من قبل العميل',
                'السيارة قيد الصيانة',
                'تم الانتهاء من الصيانة',*/
            ];

        foreach ($checkStatus as $check){
            CheckStatus::create($check);
        }
    }
}
