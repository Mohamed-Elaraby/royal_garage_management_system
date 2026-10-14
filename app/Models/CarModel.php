<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CarModel
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Car[] $cars
 * @property-read int|null $cars_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Check[] $checks
 * @property-read int|null $checks_count
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel query()
 * @mixin \Eloquent
 */
class CarModel extends Model
{
    protected $fillable = ['name'];

    public function checks()
    {
        return $this->hasMany(Check::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
