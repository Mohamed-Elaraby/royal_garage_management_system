<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Salary
 *
 * @property int $id
 * @property float|null $main
 * @property float|null $housing_allowance
 * @property float|null $transfer_allowance
 * @property float|null $travel_allowance
 * @property float|null $end_service_allowance
 * @property float|null $other_allowance
 * @property float|null $description_of_other_allowance
 * @property int $employee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Salary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Salary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Salary query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Salary getDataWithEmployee()
 */
class Salary extends Model
{
    protected $guarded = [];
//    protected $fillable = [
//        'main',
//        'housing_allowance',
//        'transfer_allowance',
//        'travel_allowance',
//        'end_service_allowance',
//        'other_allowance',
//        'description_of_other_allowance',
//        'employee_id',
//];
    public function scopeCheckEmployeeBranch($query)
    {
        if (auth()->user()->branch_id != '')
        {
            $query -> whereHas('employee', function ($q){
                $q -> where('branch_id', auth()->user()->branch_id);
            });
        }
    }
    public function scopeGetDataWithEmployee($query)
    {
        if (request('employee_id'))
        {
            $employee_id = request('employee_id');
            $query -> where('employee_id', $employee_id);
        }
    }

    public function employee()
    {
        return $this -> belongsTo(Employee::class);
    }

//    public function statements ()
//    {
//        return $this->hasMany(Statement::class);
//    }
}
