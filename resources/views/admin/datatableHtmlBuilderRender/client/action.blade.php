<select id="selectAction" class="form-control">
    <option value="" disabled selected>{{ __('trans.action') }}</option>
    <option value="{{ route('admin.cars.index', ['client_id' => $query->id]) }}" >{{ __('trans.client cars') . ' ' .$query->name}}</option>
    <option value="{{ route('admin.check.index', ['client_id' => $query->id]) }}" >{{ __('trans.client check') . ' ' .$query->name}}</option>

    @if (Auth::user()->hasPermission('create-clientPayments'))
        <option value="{{ route('admin.clientPayments.create', ['client_id' => $query->id]) }}">{{ __('trans.pay to client') . ' ' .$query->name }}</option>
    @endif

    @if (Auth::user()->hasPermission('create-clientCollecting'))
        <option value="{{ route('admin.clientCollecting.create', ['client_id' => $query->id]) }}">{{ __('trans.collection from client') . ' ' .$query->name }}</option>
    @endif

    @if (Auth::user()->hasPermission('read-clientTransactions'))
        <option value="{{ route('admin.clientTransactions', $query->id) }}">{{ __('trans.client transactions') . ' ' .$query->name }}</option>
    @endif
</select>



@if (Auth::user()->hasPermission('update-clients'))
    <a href="{{ route('admin.clients.edit', $query->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> {{ __('trans.edit') }}</a>
@endif
@if (Auth::user()->hasPermission('delete-clients'))
    {!! Form::open(['route' => ['admin.clients.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
    {!! Form::close() !!}
@endif
