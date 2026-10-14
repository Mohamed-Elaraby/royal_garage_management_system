<?php

namespace App\Models;

use App\Observers\CheckObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Check
 *
 * @property int $id
 * @property string $check_number
 * @property string $counter_number
 * @property string $chassis_number
 * @property string $plate_number
 * @property string $car_color
 * @property string|null $driver_name
 * @property string $fuel_level
 * @property string $car_status_report
 * @property string|null $car_status_report_note
 * @property string|null $car_images_note
 * @property int $check_status_id 1=>carUnderCheck, 2=>waitingClientResponse, 3=>clientApproved, 4=>carUnderMaintenance, 5=>maintenanceComplete, 6=>carExit
 * @property string $car_type
 * @property string $car_size
 * @property string|null $car_model
 * @property string|null $car_engine
 * @property string|null $car_development_code
 * @property int $client_id
 * @property int $user_id
 * @property int $branch_id
 * @property int|null $car_id
 * @property int|null $engineer_id
 * @property int $client_approved 0=>disabled, 1 =>enabled
 * @property string|null $management_notes
 * @property \Illuminate\Support\Carbon|null $car_exit_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\Car|null $car
 * @property-read \App\Models\CarDevelopmentCode $carDevelopmentCode
 * @property-read \App\Models\CarEngine $carEngine
 * @property-read \App\Models\CarModel $carModel
 * @property-read \App\Models\CarSize $carSize
 * @property-read \App\Models\CarType $carType
 * @property-read \App\Models\CheckStatus $checkStatus
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\Engineer|null $engineer
 * @property-read \App\Models\File|null $file
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Images[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PriceList[] $priceLists
 * @property-read int|null $price_lists_count
 * @property-read \App\Models\CheckStatus $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Technical[] $technicals
 * @property-read int|null $technicals_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Check checkUserRole()
 * @method static \Illuminate\Database\Eloquent\Builder|Check newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Check newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Check query()
 * @method static \Illuminate\Database\Eloquent\Builder|Check selectBranchUser()
 * @mixin \Eloquent
 */
class Check extends Model
{
    protected $fillable = [
        'check_number' ,
        'counter_number' ,
        'chassis_number',
        'plate_number' ,
        'car_color',
        'car_status_report',
        'car_status_report_note',
        'car_images_note',
        'check_status_id',
        'client_id',
        'user_id',
        'branch_id',
        'car_exit_date',
        'fuel_level',
//        'technical_id',
        'engineer_id',
        'car_type',
        'car_size',
        'car_model',
        'car_engine',
        'car_development_code',
        'car_id',
        'driver_name',
        'management_notes'
    ];

    protected $hidden = ['pivot'];

    protected $casts = [
        'car_exit_date' => 'datetime',
    ];

    protected function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y h:i:s a');
    }


    protected function getCarStatusReportAttribute($value)
    {
        return nl2br($value);
    }

    protected function getCarStatusReportNoteAttribute($value)
    {
        return nl2br($value);
    }

    protected function getCarImagesNoteAttribute($value)
    {
        return nl2br($value);
    }

    protected function setChassisNumberAttribute($value)
    {
        return $this -> attributes['chassis_number'] = strtoupper($value);
    }

    protected static function boot()
    {
        parent::boot();
        Check::observe(CheckObserver::class);
    }

    public function scopeCheckUserRole($query)
    {
        if (!Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer', 'deputy_manager']))
        {
            $query -> where('branch_id', Auth::user()->branch_id);
        }
    }

    public function scopeSelectBranchUser ($query)
    {
        return $query->where('branch_id', Auth::user()->branch_id);
    }

    public function client(): BelongsTo
    {
        return $this -> belongsTo(Client::class);
    }


    public function checkStatus(): BelongsTo
    {
        return $this -> belongsTo(CheckStatus::class);
    }

    public function user(): BelongsTo
    {
        return $this -> belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this -> hasMany(Images::class);
    }

    public function branch(): BelongsTo
    {
        return $this -> belongsTo(Branch::class);
    }

    public function status(): BelongsTo
    {
        return $this -> belongsTo(CheckStatus::class);
    }

    public function technicals(): BelongsToMany
    {
        return $this -> belongsToMany(Technical::class, 'relation_check_technicals', 'check_id', 'technical_id');
    }

    public function engineer(): BelongsTo
    {
        return $this -> belongsTo(Engineer::class);
    }

    public function carType(): BelongsTo
    {
        return $this -> belongsTo(CarType::class);
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

    public function car(): BelongsTo
    {
        return $this -> belongsTo(Car::class);
    }

    public function file(): hasOne
    {
        return $this -> hasOne(File::class);
    }

    public function saleOrder()
    {
        return $this -> hasOne(SaleOrder::class);
    }

    public function priceLists(): HasMany
    {
        return $this -> hasMany(PriceList::class);
    }

}
