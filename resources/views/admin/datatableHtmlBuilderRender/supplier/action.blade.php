<select id="selectAction" class="form-control">

    <option value="" selected disabled>{{ __('trans.action') }}</option>

    @if (Auth::user()->hasPermission('create-supplierPayments'))
        <option value="{{ route('admin.supplierPayments.create', ['supplier_id' => $query->id]) }}">{{ __('trans.pay to supplier') . ' ' .$query->name }}</option>
    @endif

    @if (Auth::user()->hasPermission('create-supplierCollecting'))
        <option value="{{ route('admin.supplierCollecting.create', ['supplier_id' => $query->id]) }}">{{ __('trans.collection from supplier') . ' ' .$query->name }}</option>
    @endif

    @if (Auth::user()->hasPermission('read-supplierTransactions'))
        <option value="{{ route('admin.supplierTransactions', $query->id) }}">{{ __('trans.supplier transactions') . ' ' .$query->name }}</option>
    @endif

    @if (Auth::user()->hasPermission('create-purchaseOrders'))
        <option value="{{ route('admin.purchaseOrders.create', ['supplier_id' => $query->id]) }}">اصدار فاتورة مشتريات من المورد {{ $query->name }}</option>
    @endif

</select>

@if (Auth::user()->hasPermission('update-suppliers'))

    <a href="{{ route('admin.suppliers.edit', $query->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> {{ __('trans.edit') }}</a>

@endif

@if (Auth::user()->hasPermission('delete-suppliers'))

    {!! Form::open(['route' => ['admin.suppliers.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
    {!! Form::close() !!}

@endif
