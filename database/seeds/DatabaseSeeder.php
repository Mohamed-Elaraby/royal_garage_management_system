<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call(LaratrustSeeder::class);
       $this->call(RolesManagerTableSeeder::class);
       $this->call(UsersTableSeeder::class);
       $this->call(JobTitlesTableSeeder::class);
       $this->call(CheckStatusTableSeeder::class);
       $this->call(BranchesTableSeeder::class);
//        $this->call(TechnicalsTableSeeder::class);
//        $this->call(EngineersTableSeeder::class);
       $this->call(CarTypesTableSeeder::class);
       $this->call(CarSizesTableSeeder::class);
       $this->call(CarModelsTableSeeder::class);
       $this->call(CarEnginesTableSeeder::class);
       $this->call(CarDevelopmentCodeTableSeeder::class);
//        $this->call(ChecksTableSeeder::class);
       $this->call(ClientsTableSeeder::class);
       $this->call(SuppliersTableSeeder::class);
       $this->call(CategoriesTableSeeder::class);
       $this->call(SubCategoriesTableSeeder::class);
       $this->call(ProductCodeTableSeeder::class);
//        $this->call(ExpensesTypeTableSeeder::class);
//        $this->call(ProductsTableSeeder::class);
//        $this->call(PlateNumberSourceTableSeeder::class);
//        $this->call(PlateNumberColorTableSeeder::class);
//        $this->call(PlateNumberColorTableSeeder::class);
//        $this->call(EmployeesTableSeeder::class);
        $this->call(NationalitiesTableSeeder::class);
    }
}
