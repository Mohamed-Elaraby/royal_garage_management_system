<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Discount
 *
 * @property int $id
 * @property float $amount
 * @property string|null $notes
 * @property int $employee_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Discount getDataWithEmployee()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount query()
 * @mixin \Eloquent
 */
class Discount extends Model
{
    protected $guarded = [];

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

    public function user()
    {
        return $this -> belongsTo(User::class);
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
