<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryYears extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function employeeSalaryLogs()
    {
        return $this->hasMany(EmployeeSalaryLog::class);
    }
}
