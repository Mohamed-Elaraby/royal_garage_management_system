@if (Auth::user()->hasPermission('update-subCategories'))
    <a href="{{ route('admin.subCategories.edit', $query->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> {{ __('trans.edit') }}</a>
@endif
@if (Auth::user()->hasPermission('delete-subCategories'))
    {!! Form::open(['route' => ['admin.subCategories.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button id="remove_button" class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
    {!! Form::close() !!}
@endif
