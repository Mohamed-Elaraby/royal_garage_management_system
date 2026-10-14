<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Employee
 *
 * @property int $id
 * @property string $name
 * @property string|null $birth_date
 * @property string $date_of_hiring
 * @property int $job_title_id
 * @property string|null $id_number
 * @property string|null $passport_number
 * @property int $branch_id
 * @property int $nationality_id
 * @property string|null $date_of_leaving_work
 * @property string $username
 * @property string $hashed_password
 * @property string $text_password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 * @mixin \Eloquent
 * @property string $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Advance[] $advance
 * @property-read int|null $advance_count
 * @property-read \App\Models\Branch $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Discount[] $discounts
 * @property-read int|null $discounts_count
 * @property-read \App\Models\JobTitle $jobTitle
 * @property-read \App\Models\Nationality $nationality
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reward[] $rewards
 * @property-read int|null $rewards_count
 * @property-read \App\Models\Salary|null $salary
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vacation[] $vacations
 * @property-read int|null $vacations_count
 */
class Employee  extends Authenticatable
{
    protected $guarded = [];
//    protected $fillable = [
//        'name',
//        'birth_date',
//        'date_of_hiring',
//        'job_title_id',
//        'id_number',
//        'passport_number',
//        'branch_id',
//        'nationality_id',
//        'date_of_leaving_work',
//        'username',
//        'hashed_password',
//        'text_password',
//    ];

    protected $hidden = [
        'hashed_password',
    ];

    public function scopeCheckEmployeeBranch($query)
    {
        if (auth()->user()->branch_id != '')
        {
            $query -> where('branch_id', auth()->user()->branch_id);
        }
    }

//    protected $appends = ['profile_picture_path'];
//
//    public function getProfilePicturePathAttribute()
//    {
//        return 'storage' . DIRECTORY_SEPARATOR . $this->image_path . DIRECTORY_SEPARATOR . $this->image_name;
//    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function salary()
    {
        return $this->hasOne(Salary::class);
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }

    public function vacations()
    {
        return $this->hasMany(Vacation::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function salaryLogs()
    {
        return $this->hasMany(EmployeeSalaryLog::class);
    }
}
