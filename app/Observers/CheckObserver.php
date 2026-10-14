<?php

namespace App\Observers;

use App\Models\Check;
use App\Models\Relation_check_technical;
use App\Models\SaleOrder;
use App\Models\SaleOrderProducts;

class CheckObserver
{
    /**
     * Handle the check "created" event.
     *
     * @param Check $check
     * @return void
     */
    public function created(Check $check)
    {
        //
    }

    /**
     * Handle the check "updated" event.
     *
     * @param Check $check
     * @return void
     */
    public function updated(Check $check)
    {
        //
    }


    public function deleted(Check $check)
    {
        $sale_order_id = $check -> saleOrder()->first() ? $check -> saleOrder()->first() -> id : '';
        SaleOrderProducts::where('sale_order_id', $sale_order_id)->delete(); // delete sale order products
        $check -> images() -> delete();
        $check -> technicals() -> detach();
        $check -> saleOrder() -> delete();
    }

    /**
     * Handle the check "restored" event.
     *
     * @param Check $check
     * @return void
     */
    public function restored(Check $check)
    {
        //
    }

    /**
     * Handle the check "force deleted" event.
     *
     * @param Check $check
     * @return void
     */
    public function forceDeleted(Check $check)
    {
        //
    }
}
