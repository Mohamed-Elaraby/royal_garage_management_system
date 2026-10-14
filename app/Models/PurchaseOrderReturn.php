<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PurchaseOrderReturn
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
 * @property int $purchase_order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bank[] $banks
 * @property-read int|null $banks_count
 * @property-read \App\Models\Branch $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MoneySafe[] $moneySafes
 * @property-read int|null $money_safes_count
 * @property-read \App\Models\PurchaseOrder $purchaseOrder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PurchaseOrderReturnProducts[] $purchaseOrderReturnProducts
 * @property-read int|null $purchase_order_return_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplierTransaction[] $supplierTransactions
 * @property-read int|null $supplier_transactions_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrderReturn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrderReturn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrderReturn query()
 * @mixin \Eloquent
 */
class PurchaseOrderReturn extends Model
{
    protected $fillable = [
        'invoice_number', 'invoice_date', 'total', 'discount', 'taxable_amount', 'total_vat', 'total_return_items', 'user_id', 'branch_id', 'notes', 'purchase_order_id'
    ];

    protected function getNotesNoteAttribute($value)
    {
        return nl2br($value);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseOrderReturnProducts()
    {
        return $this->hasMany(PurchaseOrderReturnProducts::class);
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

    public function supplierTransactions()
    {
        return $this->hasMany(SupplierTransaction::class);
    }

    public function statements ()
    {
        return $this->hasMany(Statement::class);
    }
}
