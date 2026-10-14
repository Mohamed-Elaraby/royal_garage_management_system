<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bank
 *
 * @property int $id
 * @property float $amount_paid
 * @property float $final_amount
 * @property int|null $money_process_type 0 => subtract, 1 => addition
 * @property string|null $notes
 * @property int|null $processType 0 => withdrawal, 1 => deposit
 * @property int $user_id
 * @property int|null $sale_order_id
 * @property int|null $purchase_order_id
 * @property int|null $client_payment_id
 * @property int|null $client_collecting_id
 * @property int|null $supplier_payment_id
 * @property int|null $supplier_collecting_id
 * @property int|null $sale_order_return_id
 * @property int|null $purchase_order_return_id
 * @property int|null $branch_id
 * @property int|null $expenses_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SupplierCollecting|null $SupplierCollecting
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\ClientCollecting|null $clientCollecting
 * @property-read \App\Models\ClientPayment|null $clientPayment
 * @property-read \App\Models\Expenses|null $expenses
 * @property-read \App\Models\PurchaseOrder|null $purchaseOrder
 * @property-read \App\Models\PurchaseOrderReturn|null $purchaseOrderReturn
 * @property-read \App\Models\SaleOrder|null $saleOrder
 * @property-read \App\Models\SaleOrderReturn|null $saleOrderReturn
 * @property-read \App\Models\SupplierPayment|null $supplierPayment
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Bank checkUserRole()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank query()
 * @mixin \Eloquent
 */
class Bank extends Model
{
    protected $fillable = [
        'amount_paid','final_amount', 'money_process_type', 'notes', 'processType', 'user_id', 'sale_order_id', 'purchase_order_id', 'branch_id', 'expenses_id', 'client_payment_id', 'client_collecting_id', 'supplier_payment_id', 'supplier_collecting_id', 'sale_order_return_id', 'purchase_order_return_id'
    ];

    public function scopeCheckUserRole($query)
    {
        if (!Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'deputy_manager']))
        {
            $query -> where('created_at', Carbon::today());
        }
    }

    public function setAmountPaidAttribute($value)
    {
        return $this -> attributes['amount_paid'] = number_format((float)$value, 2, '.', '');
    }

    public function setFinalAmountAttribute($value)
    {
        return $this -> attributes['final_amount'] = number_format((float)$value, 2, '.', '');
    }

//    public function getProcessTypeAttribute($value)
//    {
//        if ($value == 0)
//        {
//            return 'عملية سحب';
//        }
//        if ($value == 1)
//        {
//            return 'عملية ايداع';
//        }
//    }

    public function saleOrder()
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->belongsTo(Expenses::class);
    }

    public function clientPayment()
    {
        return $this->belongsTo(ClientPayment::class);
    }

    public function clientCollecting()
    {
        return $this->belongsTo(ClientCollecting::class);
    }

    public function supplierPayment()
    {
        return $this->belongsTo(SupplierPayment::class);
    }

    public function SupplierCollecting()
    {
        return $this->belongsTo(SupplierCollecting::class);
    }

    public function saleOrderReturn()
    {
        return $this->belongsTo(SaleOrderReturn::class);
    }

    public function purchaseOrderReturn()
    {
        return $this->belongsTo(PurchaseOrderReturn::class);
    }

    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function employeeSalaryLog()
    {
        return $this->belongsTo(EmployeeSalaryLog::class);
    }

    public function statements ()
    {
        return $this->hasMany(Statement::class);
    }

}
