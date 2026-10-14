<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Car
 *
 * @property int $id
 * @property string $chassis_number
 * @property string $plate_number
 * @property string $car_color
 * @property int $client_id
 * @property int $car_type_id
 * @property int $car_size_id
 * @property int|null $car_model_id
 * @property int|null $car_engine_id
 * @property int|null $car_development_code_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CarDevelopmentCode|null $carDevelopmentCode
 * @property-read \App\Models\CarEngine|null $carEngine
 * @property-read \App\Models\CarModel|null $carModel
 * @property-read \App\Models\CarSize $carSize
 * @property-read \App\Models\CarType $carType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Check[] $checks
 * @property-read int|null $checks_count
 * @property-read \App\Models\Client $client
 * @method static \Illuminate\Database\Eloquent\Builder|Car newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Car newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Car query()
 * @mixin \Eloquent
 */
class Car extends Model
{
    protected $fillable = [
//        'counter_number' ,
        'chassis_number',
        'plate_number' ,
        'car_color',
        'client_id',
        'car_type_id',
        'car_size_id',
        'car_model_id',
        'car_engine_id',
        'car_development_code_id',
    ];

    protected function setChassisNumberAttribute($value)
    {
        return $this -> attributes['chassis_number'] = strtoupper($value);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }

    public function carSize(): BelongsTo
    {
        return $this -> belongsTo(CarSize::class);
    }

    public function carModel(): BelongsTo
    {
        return $this -> belongsTo(CarModel::class);
    }

    public function carEngine(): BelongsTo
    {
        return $this -> belongsTo(CarEngine::class);
    }

    public function carDevelopmentCode(): BelongsTo
    {
        return $this -> belongsTo(CarDevelopmentCode::class);
    }

    public function checks()
    {
        return $this->hasMany(Check::class);
    }
}
