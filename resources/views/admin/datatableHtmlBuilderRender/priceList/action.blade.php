@if (Auth::user()->hasPermission('update-priceList'))
    <a href="{{ route('admin.priceList.edit', $query->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> {{ __('trans.edit') }}</a>
@endif
@if (Auth::user()->hasPermission('delete-priceList'))
    {!! Form::open(['route' => ['admin.priceList.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
    {!! Form::close() !!}
@endif
