<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SupplierPayment
 *
 * @property int $id
 * @property string|null $receipt_number
 * @property float|null $amount_paid
 * @property float|null $amount_paid_bank
 * @property string|null $payment_method
 * @property string|null $payment_method_bank
 * @property string|null $notes
 * @property string $payment_date
 * @property int $user_id
 * @property int|null $branch_id
 * @property int $supplier_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bank[] $banks
 * @property-read int|null $banks_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MoneySafe[] $moneySafes
 * @property-read int|null $money_safes_count
 * @property-read \App\Models\Supplier $supplier
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SupplierTransaction[] $supplierTransactions
 * @property-read int|null $supplier_transactions_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplierPayment query()
 * @mixin \Eloquent
 */
class SupplierPayment extends Model
{
    protected $fillable = [
        'receipt_number', 'amount_paid', 'amount_paid_bank', 'payment_method', 'payment_method_bank', 'notes', 'payment_date', 'user_id', 'branch_id', 'supplier_id'
    ];

    protected function getNotesAttribute($value)
    {
        return nl2br($value);
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function branch ()
    {
        return $this->belongsTo(Branch::class);
    }

    public function supplier ()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function supplierTransactions ()
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

    public function statements ()
    {
        return $this->hasMany(Statement::class);
    }

}
