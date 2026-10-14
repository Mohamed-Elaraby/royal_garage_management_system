<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Vacation
 *
 * @property int $id
 * @property string $start_vacation
 * @property string|null $end_vacation
 * @property string|null $extend_vacation
 * @property string $type
 * @property int|null $total_days
 * @property string $status
 * @property float|null $discount_amount
 * @property int $employee_id
 * @property int $user_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation getDataWithEmployee()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vacation query()
 * @mixin \Eloquent
 */
class Vacation extends Model
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

//    public function statements ()
//    {
//        return $this->hasMany(Statement::class);
//    }
}
