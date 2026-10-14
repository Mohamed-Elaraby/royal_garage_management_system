<?php

namespace App\Models;

use App\Observers\ClientObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Client
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string|null $building_number
 * @property string|null $street_name
 * @property string|null $district
 * @property string|null $city
 * @property string|null $country
 * @property string|null $postal_code
 * @property string|null $vat_number
 * @property float $balance
 * @property string|null $employer
 * @property string|null $other_car
 * @property string|null $how_you_now_us
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CarType[] $carTypes
 * @property-read int|null $car_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientTransaction[] $clientTransactions
 * @property-read int|null $client_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SaleOrder[] $saleOrders
 * @property-read int|null $sale_orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @mixin \Eloquent
 */
class Client extends Model
{
    protected $fillable = ['name', 'phone', 'building_number', 'street_name', 'district', 'city', 'country', 'postal_code', 'vat_number', 'employer', 'other_car', 'how_you_now_us', 'balance'];

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value) -> format('d/m/Y h:i:s a');
    }

    public function setBalanceAttribute($value)
    {
        return $this->attributes['balance'] = number_format($value, 2, '.', '');

    }    public function getBalanceAttribute($value)
    {
        return number_format($value, 2, '.', '');
    }

    protected static function boot()
    {
        parent::boot();
        Client::observe(ClientObserver::class);
    }

    public function checks()
    {
        return $this -> hasMany(Check::class);
    }

    public function cars()
    {
        return $this -> hasMany(Car::class);
    }

    public function carTypes()
    {
        return $this->belongsToMany(CarType::class, 'relation_car_type_clients', 'client_id', 'car_type_id');
    }

    public function clientPayments()
    {
        return $this -> hasMany(ClientPayment::class);
    }
    public function clientCollectings()
    {
        return $this -> hasMany(ClientCollecting::class);
    }

    public function clientTransactions ()
    {
        return $this->hasMany(ClientTransaction::class);
    }

    public function saleOrders ()
    {
        return $this->hasMany(SaleOrder::class);
    }
}
