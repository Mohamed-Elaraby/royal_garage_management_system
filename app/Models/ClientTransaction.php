<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ClientTransaction
 *
 * @property int $id
 * @property float $total_amount
 * @property string|null $details
 * @property float|null $amount_paid
 * @property float|null $amount_paid_bank
 * @property float|null $amount_paid_add_to_client_balance
 * @property float|null $amount_due
 * @property string $transaction_date
 * @property string|null $transaction_type
 * @property float|null $debit
 * @property float|null $credit
 * @property float|null $client_balance
 * @property int $user_id
 * @property int $client_id
 * @property int|null $sale_order_id
 * @property int|null $sale_order_return_id
 * @property int|null $client_payment_id
 * @property int|null $client_collecting_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\ClientCollecting|null $clientCollecting
 * @property-read \App\Models\ClientPayment|null $clientPayment
 * @property-read \App\Models\SaleOrder|null $saleOrder
 * @property-read \App\Models\SaleOrderReturn|null $saleOrderReturn
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTransaction query()
 * @mixin \Eloquent
 */
class ClientTransaction extends Model
{
//    protected $fillable = [
//        'total_amount', 'details', 'amount_paid', 'amount_paid_bank', 'amount_paid_add_to_client_balance', 'amount_due', 'transaction_date','debit', 'credit', 'user_id', 'client_id', 'sale_order_id', 'sale_order_return_id', 'payment_id', 'collecting_id'
//    ];

    protected $guarded = [];

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function client ()
    {
        return $this->belongsTo(Client::class);
    }

    public function clientPayment ()
    {
        return $this->belongsTo(ClientPayment::class);
    }

    public function clientCollecting ()
    {
        return $this->belongsTo(ClientCollecting::class);
    }

    public function saleOrder ()
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function saleOrderReturn ()
    {
        return $this->belongsTo(SaleOrderReturn::class);
    }
}
