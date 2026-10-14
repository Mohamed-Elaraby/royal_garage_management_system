<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmployeeSalaryLog
 *
 * @property int $id
 * @property float|null $main
 * @property float|null $housing_allowance
 * @property float|null $transfer_allowance
 * @property float|null $travel_allowance
 * @property float|null $end_service_allowance
 * @property float|null $other_allowance
 * @property string|null $description_of_other_allowance
 * @property float|null $advances
 * @property float|null $rewards
 * @property float|null $vacations
 * @property float|null $discounts
 * @property int $employee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalaryLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalaryLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSalaryLog query()
 * @mixin \Eloquent
 */
class EmployeeSalaryLog extends Model
{
    protected $guarded = [];

    public function employee()
    {
        return $this -> belongsTo(Employee::class);
    }

    public function salaryYear()
    {
        return $this -> belongsTo(SalaryYears::class);
    }

    public function moneySafes()
    {
        return $this -> hasMany(MoneySafe::class);
    }

    public function banks()
    {
        return $this -> hasMany(Bank::class);
    }

    public function statements ()
    {
        return $this->hasMany(Statement::class);
    }

}
