<?php

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee = Employee::create([
            'name' => 'Mohamed',
            'birth_date' => '1983-07-14',
            'date_of_hiring' => '2023-03-17',
            'job_title_id' => 4,
            'id_number' => '24575686868',
            'passport_number' => '4558242436',
            'branch_id' => 3,
            'nationality_id' => 1,
            'date_of_leaving_work' => null,
            'username' => '82290871',
            'hashed_password' => '$2y$10$eCU3p3.D.1AbKWmrziuYAe6rYAtiOuUK7HPQ7aI.J6jX/N3RwtnBK',
            'text_password' => '6aB8oUSg',
        ]);

        $employee -> salary() ->create(['main'=>3500]);
    }
}
