<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CarSize
 *
 * @property int $id
 * @property string $name
 * @property int $car_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CarType $carType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Car[] $cars
 * @property-read int|null $cars_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Check[] $checks
 * @property-read int|null $checks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CarDevelopmentCode[] $developmentCodes
 * @property-read int|null $development_codes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CarEngine[] $engines
 * @property-read int|null $engines_count
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarSize query()
 * @mixin \Eloquent
 */
class CarSize extends Model
{
    protected $fillable = ['name', 'car_type_id'];


    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }

    public function engines()
    {
        return $this->hasMany(CarEngine::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function checks()
    {
        return $this->hasMany(Check::class);
    }

    public function developmentCodes()
    {
        return $this->hasMany(CarDevelopmentCode::class);
    }
}
