<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SaleOrderReturn
 *
 * @property int $id
 * @property string|null $invoice_number
 * @property string $invoice_date
 * @property float $total
 * @property float|null $discount
 * @property float $taxable_amount
 * @property float $total_vat
 * @property float $total_return_items
 * @property int $user_id
 * @property int $branch_id
 * @property string|null $notes
 * @property int $sale_order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bank[] $banks
 * @property-read int|null $banks_count
 * @property-read \App\Models\Branch $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientTransaction[] $clientTransactions
 * @property-read int|null $client_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MoneySafe[] $moneySafes
 * @property-read int|null $money_safes_count
 * @property-read \App\Models\SaleOrder $saleOrder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SaleOrderReturnProducts[] $saleOrderReturnProducts
 * @property-read int|null $sale_order_return_products_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrderReturn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrderReturn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrderReturn query()
 * @mixin \Eloquent
 */
class SaleOrderReturn extends Model
{
    protected $fillable = [
        'invoice_number', 'invoice_date', 'total', 'discount', 'taxable_amount', 'total_vat', 'total_return_items', 'user_id', 'branch_id', 'notes', 'sale_order_id'
    ];

    protected function getNotesNoteAttribute($value)
    {
        return nl2br($value);
    }

    public function saleOrder()
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function saleOrderReturnProducts()
    {
        return $this->hasMany(SaleOrderReturnProducts::class);
    }

    public function moneySafes ()
    {
        return $this->hasMany(MoneySafe::class);
    }

    public function banks ()
    {
        return $this->hasMany(Bank::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clientTransactions()
    {
        return $this->hasMany(ClientTransaction::class);
    }

    public function statements ()
    {
        return $this->hasMany(Statement::class);
    }
}
