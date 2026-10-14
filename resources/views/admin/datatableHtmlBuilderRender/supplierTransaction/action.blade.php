@if ($query->purchaseOrder)
    <a class="btn btn-warning" href="{{ route('admin.purchaseOrders.show', $query -> purchaseOrder -> id) }}">{{ __('trans.purchase order') }}</a>
@elseif($query->supplierPayment)
    <a class="btn btn-danger" href="{{ route('admin.supplierPayments.show', $query -> supplierPayment -> id) }}">{{ __('trans.supplier payment') }}</a>
@elseif($query->supplierCollecting)
    <a class="btn btn-success" href="{{ route('admin.supplierCollecting.show', $query -> supplierCollecting -> id) }}">{{ __('trans.supplier collecting') }}</a>
@elseif($query->purchaseOrderReturn)
    <a class="btn btn-primary" href="{{ route('admin.purchaseOrderReturns.show', $query -> purchaseOrderReturn -> id) }}">{{ __('trans.purchase order return') }}</a>
@endif
