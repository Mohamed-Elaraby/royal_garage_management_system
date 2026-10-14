<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PurchaseOrderProducts
 *
 * @property int $id
 * @property string $item_code
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
 * @property int|null $sub_category_id
 * @property int $purchase_order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PurchaseOrder $openPurchaseOrder
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrderProducts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrderProducts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrderProducts query()
 * @mixin \Eloquent
 */
class PurchaseOrderProducts extends Model
{
    protected $fillable = ['item_code', 'item_name', 'item_quantity', 'item_price', 'item_amount_taxable', 'item_discount','item_discount_type', 'item_discount_amount', 'item_sub_total_after_discount', 'item_tax_amount',  'item_sub_total', 'sub_category_id', 'purchase_order_id'];

    public function openPurchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
