<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Expenses
 *
 * @property int $id
 * @property float $amount
 * @property string|null $notes
 * @property string $expenses_date
 * @property string|null $payment_method
 * @property int $expenses_type_id
 * @property int $user_id
 * @property int $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bank[] $banks
 * @property-read int|null $banks_count
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\ExpensesType $expensesType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MoneySafe[] $moneySafes
 * @property-read int|null $money_safes_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses query()
 * @mixin \Eloquent
 */
class Expenses extends Model
{
    protected $fillable = [
        'amount', 'notes', 'expenses_date', 'user_id', 'branch_id', 'expenses_type_id', 'payment_method'
    ];

    protected function getNotesAttribute($value)
    {
        return nl2br($value);
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function branch ()
    {
        return $this->belongsTo(Branch::class);
    }

    public function expensesType ()
    {
        return $this->belongsTo(ExpensesType::class);
    }

    public function moneySafes()
    {
        return $this->hasMany(MoneySafe::class);
    }

    public function banks()
    {
        return $this->hasMany(Bank::class);
    }

    public function statements ()
    {
        return $this->hasMany(Statement::class);
    }
}
