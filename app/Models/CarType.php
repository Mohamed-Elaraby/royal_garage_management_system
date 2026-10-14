<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CarType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CarSize[] $carSizes
 * @property-read int|null $car_sizes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Car[] $cars
 * @property-read int|null $cars_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Check[] $checks
 * @property-read int|null $checks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Client[] $clients
 * @property-read int|null $clients_count
 * @method static \Illuminate\Database\Eloquent\Builder|CarType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarType query()
 * @mixin \Eloquent
 */
class CarType extends Model
{
    protected $fillable = ['name'];

    public function carSizes()
    {
        return $this->hasMany(CarSize::class);
    }

    public function checks()
    {
        return $this->hasMany(Check::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'relation_car_type_clients', 'car_type_id', 'client_id');
    }
}
