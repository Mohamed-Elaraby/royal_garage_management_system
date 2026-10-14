<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CheckStatus
 *
 * @property int $id
 * @property string $name
 * @property string|null $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CheckStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckStatus query()
 * @mixin \Eloquent
 */
class CheckStatus extends Model
{
    protected $fillable = ['name', 'color'];

    public function check()
    {
        return $this -> hasMany(Check::class);
    }
}
