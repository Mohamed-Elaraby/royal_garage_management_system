<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ScheduledAdvance
 *
 * @property int $id
 * @property float $amount
 * @property string $status
 * @property string|null $payment_method
 * @property string|null $date_of_payment
 * @property int $advance_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledAdvance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledAdvance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduledAdvance query()
 * @mixin \Eloquent
 */
class ScheduledAdvance extends Model
{
    protected $guarded = [];

    public function advance()
    {
        return $this -> belongsTo(Advance::class);
    }

//    public function statements ()
//    {
//        return $this->hasMany(Statement::class);
//    }
}
