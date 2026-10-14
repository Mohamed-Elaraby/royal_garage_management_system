<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Nationality
 *
 * @property int $id
 * @property string $nationality
 * @method static \Illuminate\Database\Eloquent\Builder|Nationality newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nationality newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nationality query()
 * @mixin \Eloquent
 */
class Nationality extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function employees()
    {
        return $this -> hasMany(Employee::class);
    }
}
