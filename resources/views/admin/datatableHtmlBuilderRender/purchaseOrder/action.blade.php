@if (request('status') == 'open')
    @if (Auth::user()->hasPermission('update-openPurchaseOrders'))
        <a href="{{ route('admin.purchaseOrders.edit', $query->id) }}" class="btn btn-sm btn-success"> <i class="fa fa-edit"></i> تعديل او اغلاق الفاتورة </a>
    @endif
    @if (Auth::user()->hasPermission('delete-openPurchaseOrders'))
        {!! Form::open(['route' => ['admin.purchaseOrders.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
        <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
        {!! Form::close() !!}
    @endif
@else
    <a href="{{ route('admin.purchaseOrderReturns.create', ['purchase_order_id' => $query->id]) }}" class="btn btn-sm btn-primary"> <i class="fa fa-reply"></i> انشاء فاتورة مردودات </a>
@endif
