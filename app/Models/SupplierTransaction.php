<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SupplierTransaction
 *
 * @property int $id
 * @property float $total_amount
 * @property string|null $details
 * @property float|null $amount_paid
 * @property float|null $amount_paid_bank
 * @property float|null $amount_paid_subtract_from_supplier_balance
 * @property float|null $amount_due
 * @property string $transaction_date
 * @property string|null $transaction_type
 * @property float|null $debit
 * @property float|null $credit
 * @property float|null $supplier_discount_on_purchase_order
 * @property float|null $supplier_balance
 * @property int $user_id
 * @property int $supplier_id
 * @property int|null $purchase_order_id
 * @property int|null $purchase_order_return_id
 * @property int|null $supplier_payment_id
 * @property int|null $supplier_collecting_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PurchaseOrder|null $purchaseOrder
 * @property-read \App\Models\PurchaseOrderReturn|null $purchaseOrderReturn
 * @property-read \App\Models\Supplier $supplier
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierTransaction query()
 * @mixin \Eloquent
 */
class SupplierTransaction extends Model
{
//    protected $fillable = ['total_amount', 'details', 'amount_paid', 'amount_paid_bank', 'amount_due', 'transaction_date', 'transaction_type','debit', 'credit', 'user_id', 'supplier_id', 'purchase_order_id', 'purchase_order_return_id', 'supplier_discount_on_purchase_order', 'supplier_payment_id', 'supplier_collecting_id'];
    protected $guarded = [];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplierPayment()
    {
        return $this -> belongsTo(SupplierPayment::class);
    }

    public function supplierCollecting()
    {
        return $this -> belongsTo(SupplierCollecting::class);
    }

    public function purchaseOrderReturn ()
    {
        return $this->belongsTo(PurchaseOrderReturn::class);
    }
}
