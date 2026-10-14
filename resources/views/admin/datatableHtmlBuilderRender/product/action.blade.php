@if (Auth::user()->hasPermission('update-products'))
    <a href="{{ route('admin.products.edit', $query->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> {{ __('trans.edit') }}</a>
@endif
@if (Auth::user()->hasPermission('delete-products'))
    {!! Form::open(['route' => ['admin.products.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
    {!! Form::close() !!}
@endif
@if(auth()->user()->hasPermission('read-internalTransfer'))
<a href="{{ route('admin.product.transfer', $query->id) }}" class="btn btn-sm btn-warning"> <i class="fa fa-arrow-right"></i> {{ __('trans.transfer') }}</a>
@endif
