@if (request('status') == 'open')
    @if (Auth::user()->hasPermission('update-openSaleOrders'))
        <a href="{{ route('admin.saleOrders.edit', $query->id) }}" class="btn btn-sm btn-success"> <i class="fa fa-edit"></i> تعديل او اغلاق الفاتورة </a>
    @endif
    @if (Auth::user()->hasPermission('delete-openSaleOrders'))
        {!! Form::open(['route' => ['admin.saleOrders.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
        <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
        {!! Form::close() !!}
    @endif
@else
    @if (!Auth::user()->hasRole(['general_observer']))
        <a href="{{ route('admin.saleOrderReturns.create', ['sale_order_id' => $query->id]) }}" class="btn btn-sm btn-primary"> <i class="fa fa-reply"></i> انشاء فاتورة مردودات </a>
    @endif
@endif
