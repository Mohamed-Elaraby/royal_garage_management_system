@if (Auth::user()->hasPermission('read-advance'))
    <a href="{{ route('employee.advance.show', $query->id) }}" class="btn btn-sm btn-info"> <i class="fa fa-eye"></i> {{ __('trans.details data for employee') }}</a>
@endif

@if (Auth::user()->hasPermission('update-advance'))
    <a href="{{ route('employee.advance.edit', $query->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> {{ __('trans.edit') }}</a>
@endif

@if (Auth::user()->hasPermission('delete-advance'))
    {!! Form::open(['route' => ['employee.advance.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
    {!! Form::close() !!}
@endif
