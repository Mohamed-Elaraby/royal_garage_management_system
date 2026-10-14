<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    protected $guarded = [];

    public function clientPayment()
    {
        return $this->belongsTo(ClientPayment::class);
    }

    public function clientCollecting()
    {
        return $this->belongsTo(ClientCollecting::class);
    }

    public function saleOrder()
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function supplierPayment()
    {
        return $this->belongsTo(SupplierPayment::class);
    }

    public function supplierCollecting()
    {
        return $this->belongsTo(SupplierCollecting::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function expenses()
    {
        return $this->belongsTo(Expenses::class);
    }

    public function saleOrderReturn()
    {
        return $this->belongsTo(SaleOrderReturn::class);
    }

    public function purchaseOrderReturn()
    {
        return $this->belongsTo(PurchaseOrderReturn::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function employeeSalaryLog()
    {
        return $this->belongsTo(EmployeeSalaryLog::class);
    }

    public function moneySafe()
    {
        return $this->belongsTo(MoneySafe::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
