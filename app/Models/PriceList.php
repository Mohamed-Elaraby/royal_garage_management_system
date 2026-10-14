<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PriceList
 *
 * @property int $id
 * @property string|null $price_list_number
 * @property float $total
 * @property float|null $discount
 * @property float $taxable_amount
 * @property float $total_vat
 * @property float $total_amount_due
 * @property string $chassis_number
 * @property int $user_id
 * @property int $branch_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\Check $check
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PriceListProducts[] $priceListProducts
 * @property-read int|null $price_list_products_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceList query()
 * @mixin \Eloquent
 */
class PriceList extends Model
{
    protected $guarded = [];

    protected function getNotesAttribute($value)
    {
        return nl2br($value);
    }

    protected function getDiscountTypeAttribute($value)
    {
        return $value == 1 ? '%' : 'ر.س';
    }

    public function check()
    {
        return $this->belongsTo(Check::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function priceListProducts()
    {
        return $this->hasMany(PriceListProducts::class);
    }
}
