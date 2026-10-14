<?php

use App\Models\JobTitle;
use Illuminate\Database\Seeder;

class JobTitlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titles = ['Owner', 'المدير العام', 'مدير فرع', 'محاسب', 'Super Owner'];
        foreach ($titles as $title)
        {
            JobTitle::create([
                'name' => $title
            ]);
        }
    }
}
