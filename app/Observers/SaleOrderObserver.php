<?php

namespace App\Observers;

use App\Models\SaleOrder;

class SaleOrderObserver
{
    /**
     * Handle the sale order "created" event.
     *
     * @param  \App\Models\SaleOrder  $saleOrder
     * @return void
     */
    public function created(SaleOrder $saleOrder)
    {
        //
    }

    /**
     * Handle the sale order "updated" event.
     *
     * @param  \App\Models\SaleOrder  $saleOrder
     * @return void
     */
    public function updated(SaleOrder $saleOrder)
    {
        //
    }

    /**
     * Handle the sale order "deleted" event.
     *
     * @param  \App\Models\SaleOrder  $saleOrder
     * @return void
     */
    public function deleted(SaleOrder $saleOrder)
    {
        $saleOrder -> saleOrderProducts() -> delete();
    }

    /**
     * Handle the sale order "restored" event.
     *
     * @param  \App\Models\SaleOrder  $saleOrder
     * @return void
     */
    public function restored(SaleOrder $saleOrder)
    {
        //
    }

    /**
     * Handle the sale order "force deleted" event.
     *
     * @param  \App\Models\SaleOrder  $saleOrder
     * @return void
     */
    public function forceDeleted(SaleOrder $saleOrder)
    {
        //
    }
}
