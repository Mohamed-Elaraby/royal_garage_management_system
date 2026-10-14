<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Advance
 *
 * @property int $id
 * @property string $status
 * @property float $amount
 * @property string|null $notes
 * @property string $type
 * @property int|null $number_of_schedule
 * @property int|null $refunds
 * @property int|null $remaining_payments
 * @property int $employee_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Advance getDataWithEmployee()
 * @method static \Illuminate\Database\Eloquent\Builder|Advance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Advance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Advance query()
 * @mixin \Eloquent
 */
class Advance extends Model
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

    public function scopeCheckUserRole($query)
    {
        if (auth()->user()->branch_id != '')
        {
            $query -> where('branch_id', auth()->user()->branch_id);
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

    public function scheduledAdvances()
    {
        return $this -> hasMany(ScheduledAdvance::class);
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
