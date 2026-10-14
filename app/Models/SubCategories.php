<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SubCategories
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubCategories query()
 * @mixin \Eloquent
 */
class SubCategories extends Model
{
    protected $fillable = ['name', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function internalTransfers()
    {
        return $this->hasMany(InternalTransfer::class);
    }
}
