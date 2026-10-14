<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductCode
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductCode query()
 * @mixin \Eloquent
 */
class ProductCode extends Model
{
    protected $fillable = ['code', 'name'];

    public function getCodeAttribute($value)
    {
        $value =  str_replace(' ', '_', $value);
        return preg_replace('/[^a-zA-Z0-9_]/', '', $value);
    }

    public function setCodeAttribute($value)
    {
        $value = str_replace(' ', '_', $value); // Replaces all spaces with hyphens.
        $this->attributes['code'] = preg_replace('/[^a-zA-Z0-9_]/', '', $value);
    }
}
