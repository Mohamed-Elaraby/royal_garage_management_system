<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name' , 'phone', 'building_number', 'street_name', 'district', 'city', 'country', 'postal_code', 'vat_number', 'balance', 'vat_number'];

    public function setBalanceAttribute($value)
    {
        return $this->attributes['balance'] = number_format($value, 2, '.', '');

    }

    public function getBalanceAttribute($value)
    {
        return number_format($value, 2, '.', '');
    }

    public function purchaseOrders ()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function supplierPayments ()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    public function supplierCollectings ()
    {
        return $this->hasMany(SupplierCollecting::class);
    }

    public function supplierTransactions ()
    {
        return $this->hasMany(SupplierTransaction::class);
    }

    public function openPurchaseOrders ()
    {
        return $this->hasMany(OpenPurchaseOrder::class);
    }
}
