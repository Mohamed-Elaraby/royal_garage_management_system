<?php

namespace App\Models;

use App\Observers\PurchaseOrderObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\PurchaseOrder
 *
 * @property int $id
 * @property string|null $invoice_number
 * @property string $invoice_date
 * @property float $total
 * @property float|null $discount
 * @property float $taxable_amount
 * @property float $total_vat
 * @property float $total_amount_due
 * @property float|null $amount_paid
 * @property float|null $amount_paid_bank
 * @property float $amount_due
 * @property int|null $supplier_discount_type 0=> currency, 1=> percentage
 * @property float|null $supplier_discount
 * @property float|null $supplier_discount_amount
 * @property string $status
 * @property int $user_id
 * @property int $branch_id
 * @property int $supplier_id
 * @property string|null $payment_method
 * @property string|null $payment_method_bank
 * @property string|null $payment_receipt_number
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bank[] $banks
 * @property-read int|null $banks_count
 * @property-read \App\Models\Branch $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MoneySafe[] $moneySafes
 * @property-read int|null $money_safes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PurchaseOrderProducts[] $purchaseOrderProducts
 * @property-read int|null $purchase_order_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PurchaseOrderReturn[] $purchaseOrderReturns
 * @property-read int|null $purchase_order_returns_count
 * @property-read \App\Models\Supplier $supplier
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplierTransaction[] $supplierTransactions
 * @property-read int|null $supplier_transactions_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder checkOrderStatus()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder checkUserRole()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseOrder query()
 * @mixin \Eloquent
 */
class PurchaseOrder extends Model
{
    protected $fillable = [
        'invoice_number', 'invoice_date', 'total', 'discount', 'taxable_amount', 'total_vat', 'total_amount_due', 'amount_paid', 'amount_paid_bank', 'amount_due', 'supplier_discount', 'supplier_discount_type', 'supplier_discount_amount', 'status', 'user_id', 'branch_id', 'supplier_id', 'payment_method', 'payment_method_bank', 'payment_receipt_number', 'notes'
    ];

    protected static function boot()
    {
        parent::boot();
        PurchaseOrder::observe(PurchaseOrderObserver::class);
    }

    public function scopeCheckUserRole($query)
    {
        if (!Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer', 'deputy_manager']))
        {
            $query -> where('branch_id', Auth::user()->branch_id);
        }
    }

    public function scopeCheckOrderStatus($query)
    {
        if (request() -> has('status')) {
            $status = request('status');
            $query -> where('status', $status);
        }
    }
    protected function getNotesAttribute($value)
    {
        return nl2br($value);
    }

    protected function getDiscountTypeAttribute($value)
    {
        return $value == 1 ? '%' : 'ر.س';
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseOrderProducts()
    {
        return $this->hasMany(PurchaseOrderProducts::class);
    }

    public function supplierTransactions()
    {
        return $this->hasMany(SupplierTransaction::class);
    }

    public function moneySafes ()
    {
        return $this->hasMany(MoneySafe::class);
    }

    public function banks ()
    {
        return $this->hasMany(Bank::class);
    }

    public function purchaseOrderReturns()
    {
        return $this->hasMany(PurchaseOrderReturn::class);
    }

    public function statements ()
    {
        return $this->hasMany(Statement::class);
    }

}
