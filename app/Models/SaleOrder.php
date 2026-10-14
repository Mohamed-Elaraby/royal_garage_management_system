<?php

namespace App\Models;

use App\Observers\SaleOrderObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\SaleOrder
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
 * @property string $status
 * @property int $user_id
 * @property int $branch_id
 * @property int $client_id
 * @property int $check_id
 * @property string|null $payment_method
 * @property string|null $payment_method_bank
 * @property string|null $payment_receipt_number
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $date_of_supply
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bank[] $banks
 * @property-read int|null $banks_count
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\Check $check
 * @property-read \App\Models\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientTransaction[] $clientTransactions
 * @property-read int|null $client_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MoneySafe[] $moneySafes
 * @property-read int|null $money_safes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SaleOrderProducts[] $saleOrderProducts
 * @property-read int|null $sale_order_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SaleOrderReturn[] $saleOrderReturns
 * @property-read int|null $sale_order_returns_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrder checkOrderStatus()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrder checkUserRole()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SaleOrder query()
 * @mixin \Eloquent
 */
class SaleOrder extends Model
{
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'total',
        'discount',
        'taxable_amount',
        'total_vat',
        'total_amount_due',
        'amount_paid',
        'amount_paid_bank',
        'amount_due',
        'status',
        'user_id',
        'branch_id',
        'client_id',
        'check_id',
        'payment_method',
        'payment_method_bank',
        'payment_receipt_number',
        'notes',
        'date_of_supply',
        'hand_labour',
        'new_parts',
        'used_parts',
    ];

    protected $appends = ['date_supply', 'time_supply'];

    protected static function boot()
    {
        parent::boot();
        SaleOrder::observe(SaleOrderObserver::class);
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

    protected function getDateSupplyAttribute($value)
    {
        return $this->date_of_supply ? Carbon::parse($this->date_of_supply) -> format('d/m/Y') : '';
    }

    protected function getTimeSupplyAttribute($value)
    {
        return $this->date_of_supply ? Carbon::parse($this->date_of_supply) -> format('h:i:s a') : '';
    }

    protected function getNotesAttribute($value)
    {
        return nl2br($value);
    }

    protected function getDiscountTypeAttribute($value)
    {
        return $value == 1 ? '%' : 'ر.س';
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleOrderProducts()
    {
        return $this->hasMany(SaleOrderProducts::class);
    }

    public function clientTransactions()
    {
        return $this->hasMany(ClientTransaction::class);
    }

    public function moneySafes ()
    {
        return $this->hasMany(MoneySafe::class);
    }

    public function banks ()
    {
        return $this->hasMany(Bank::class);
    }

    public function check()
    {
        return $this->belongsTo(Check::class);
    }

    public function saleOrderReturns ()
    {
        return $this->hasMany(SaleOrderReturn::class);
    }

    public function statements ()
    {
        return $this->hasMany(Statement::class);
    }
}
