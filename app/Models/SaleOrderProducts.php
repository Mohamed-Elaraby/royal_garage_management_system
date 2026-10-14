<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SaleOrderProducts
 *
 * @property int $id
 * @property string $item_code
 * @property string $item_name
 * @property int $item_quantity
 * @property float $item_purchasing_price
 * @property float $item_price
 * @property float $item_amount_taxable
 * @property float|null $item_discount
 * @property int|null $item_discount_type 0=> currency, 1=> percentage
 * @property float|null $item_discount_amount
 * @property float|null $item_sub_total_after_discount
 * @property float $item_tax_amount
 * @property float $item_sub_total
 * @property int $sale_order_id
 * @property int|null $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\SaleOrder $saleOrder
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrderProducts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrderProducts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrderProducts query()
 * @mixin \Eloquent
 */
class SaleOrderProducts extends Model
{
    protected $fillable = ['item_code', 'item_name', 'item_quantity', 'item_purchasing_price', 'item_price', 'item_amount_taxable', 'item_discount', 'item_discount_type', 'item_discount_amount', 'item_sub_total_after_discount', 'item_tax_amount',  'item_sub_total', 'sale_order_id', 'product_id'];

    public function saleOrder()
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
