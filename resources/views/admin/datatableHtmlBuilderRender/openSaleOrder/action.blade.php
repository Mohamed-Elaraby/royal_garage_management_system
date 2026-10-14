@if (Auth::user()->hasPermission('update-openSaleOrders'))
    <a href="{{ route('admin.openSaleOrders.edit', $query->id) }}" class="btn btn-sm btn-success"> <i class="fa fa-edit"></i> تعديل او اغلاق الفاتورة </a>
@endif
@if (Auth::user()->hasPermission('delete-openSaleOrders'))
    {!! Form::open(['route' => ['admin.openSaleOrders.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
    {!! Form::close() !!}
@endif
