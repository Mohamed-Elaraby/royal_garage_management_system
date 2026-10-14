<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image_path', 'image_name', 'role_id', 'branch_id', 'job_title_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['profile_picture_path'];

    public function getProfilePicturePathAttribute()
    {
        return 'storage' . DIRECTORY_SEPARATOR . $this->image_path . DIRECTORY_SEPARATOR . $this->image_name;
    }

    public function role()
    {
        return $this -> belongsTo(Role::class);
    }

    public function check()
    {
        return $this -> hasMany(Check::class);
    }
    public function purchaseOrders()
    {
        return $this -> hasMany(PurchaseOrder::class);
    }

    public function supplierTransactions()
    {
        return $this -> hasMany(SupplierTransaction::class);
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

    public function clientTransactions()
    {
        return $this -> hasMany(ClientTransaction::class);
    }

    public function branch()
    {
        return $this -> belongsTo(Branch::class);
    }

    public function jobTitle()
    {
        return $this -> belongsTo(JobTitle::class);
    }

    public function saleOrders ()
    {
        return $this->hasMany(SaleOrder::class);
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

    public function priceLists()
    {
        return $this -> hasMany(PriceList::class);
    }

    public function moneySafe()
    {
        return $this -> hasMany(MoneySafe::class);
    }

    public function bank()
    {
        return $this -> hasMany(Bank::class);
    }

    public function internalTransfer()
    {
        return $this -> hasMany(InternalTransfer::class);
    }
}
