<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExpensesType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Expenses[] $expenses
 * @property-read int|null $expenses_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensesType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensesType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpensesType query()
 * @mixin \Eloquent
 */
class ExpensesType extends Model
{
    protected $fillable = ['name'];

    public function expenses()
    {
        return $this->hasMany(Expenses::class);
    }
}
