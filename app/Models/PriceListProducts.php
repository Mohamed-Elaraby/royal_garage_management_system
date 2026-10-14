<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PriceListProducts
 *
 * @property int $id
 * @property string $item_name
 * @property int $item_quantity
 * @property float $item_price
 * @property float $item_amount_taxable
 * @property float|null $item_discount
 * @property int|null $item_discount_type 0=> currency, 1=> percentage
 * @property float|null $item_discount_amount
 * @property float|null $item_sub_total_after_discount
 * @property float $item_tax_amount
 * @property float $item_sub_total
 * @property int $price_list_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PriceList $priceList
 * @method static \Illuminate\Database\Eloquent\Builder|PriceListProducts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceListProducts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceListProducts query()
 * @mixin \Eloquent
 */
class PriceListProducts extends Model
{

    protected $guarded = [];
    public function priceList()
    {
        return $this->belongsTo(PriceList::class);
    }
}
