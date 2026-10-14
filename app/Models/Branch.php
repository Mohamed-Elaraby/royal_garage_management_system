<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = ['name', 'display_name', 'phone', 'building_number', 'street_name', 'district', 'city', 'country', 'postal_code', 'vat_number', 'status'];

//    protected static function boot()
//    {
//        parent::boot();
//        Client::observe(ClientObserver::class);
//    }

    public function getStatusAttribute($value)
    {
        return $value == false?__('trans.closed'):__('trans.open');
    }

    public function checks(): HasMany
    {
        return $this -> hasMany(Check::class);
    }

    public function users(): HasMany
    {
        return $this -> hasMany(User::class);
    }

    public function technicals(): HasMany
    {
        return $this -> hasMany(Technical::class);
    }
    public function engineers(): HasMany
    {
        return $this -> hasMany(Engineer::class);
    }

    public function products(): HasMany
    {
        return $this -> hasMany(Product::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this -> hasMany(PurchaseOrder::class);
    }

    public function supplierPayments()
    {
        return $this -> hasMany(SupplierPayment::class);
    }
    public function supplierCollectings()
    {
        return $this -> hasMany(SupplierCollecting::class);
    }

    public function clientPayments()
    {
        return $this -> hasMany(ClientPayment::class);
    }

    public function clientCollectings()
    {
        return $this -> hasMany(ClientCollecting::class);
    }

    public function saleOrders ()
    {
        return $this->hasMany(SaleOrder::class);
    }

    public function moneySafes ()
    {
        return $this->hasMany(MoneySafe::class);
    }

    public function expenses ()
    {
        return $this->hasMany(Expenses::class);
    }


    public function openPurchaseOrders ()
    {
        return $this->hasMany(OpenPurchaseOrder::class);
    }

    public function saleOrderReturns ()
    {
        return $this->hasMany(SaleOrderReturn::class);
    }

    public function purchaseOrderReturns ()
    {
        return $this->hasMany(PurchaseOrderReturn::class);
    }

    public function priceLists(): HasMany
    {
        return $this -> hasMany(PriceList::class);
    }

    public function internalTransfer(): HasMany
    {
        return $this -> hasMany(InternalTransfer::class);
    }

    public function statements ()
    {
        return $this->hasMany(Statement::class);
    }
}
