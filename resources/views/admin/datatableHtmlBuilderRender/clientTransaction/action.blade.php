@if ($query->saleOrder)
    <a class="btn btn-warning" href="{{ route('admin.saleOrders.show', $query -> saleOrder -> id) }}">
        {{ __('trans.sale order') }}
        @if(count($query->saleOrder -> saleOrderReturns) > 0)
            <i class="fa fa-refresh"></i>
        @endif
    </a>
@elseif($query->clientPayment)
    <a class="btn btn-danger" href="{{ route('admin.clientPayments.show', $query -> clientPayment -> id) }}">{{ __('trans.client payment') }}</a>
@elseif($query->clientCollecting)
    <a class="btn btn-success" href="{{ route('admin.clientCollecting.show', $query -> clientCollecting -> id) }}">{{ __('trans.supplier collecting') }}</a>
@elseif($query->saleOrderReturn)
    <a class="btn btn-primary" href="{{ route('admin.saleOrderReturns.show', $query -> saleOrderReturn -> id) }}">{{ __('trans.sale order return') }}</a>
@endif
