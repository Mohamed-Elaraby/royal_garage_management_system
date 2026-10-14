<?php

namespace App\Models;

use App\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property float $price
 * @property float|null $discount
 * @property int|null $discount_type
 * @property float|null $discount_amount
 * @property float|null $price_after_discount
 * @property float|null $selling_price
 * @property int $quantity
 * @property int $user_id
 * @property int|null $sub_category_id
 * @property int $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SaleOrderProducts[] $saleOrderProducts
 * @property-read int|null $sale_order_products_count
 * @property-read \App\Models\SubCategories|null $subCategory
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @mixin \Eloquent
 */
class Product extends Model
{
    protected $fillable = [
        'code', 'name', 'price', 'discount', 'discount_type', 'discount_amount', 'price_after_discount','selling_price', 'quantity', 'user_id', 'sub_category_id', 'branch_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ProductScope());
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategories::class);
    }

    public function saleOrderProducts()
    {
        return $this->hasMany(SaleOrderProducts::class);
    }
}
