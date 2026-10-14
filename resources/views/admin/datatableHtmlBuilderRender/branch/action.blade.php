<select id="selectAction" class="form-control">
    <option value="" selected disabled>{{ __('trans.action') }}</option>
    <option value="{{ route('dashboard', ['branch_id' => $query->id]) }}">{{ __('trans.branch statistic') }}</option>
    @if (Auth::user()->hasPermission('read-check'))
        <option value="{{ route('admin.check.index', ['branch_id' => $query->id]) }}">{{ __('trans.branch checks') }}</option>
    @endif

    @if (Auth::user()->hasPermission('read-check'))
        <option value="{{ route('admin.check.index', ['branch_id' => $query->id, 'car_exists' => true]) }}">{{ __('trans.branch exit car now') }}</option>
    @endif

    @if (Auth::user()->hasPermission('read-users'))
        <option value="{{ route('admin.users.index', ['branch_id' => $query->id]) }}">{{ __('trans.branch users') }}</option>
    @endif

    @if (Auth::user()->hasPermission('read-technicals'))
        <option value="{{ route('admin.technicals.index', ['branch_id' => $query->id]) }}">{{ __('trans.branch technicals') }}</option>
    @endif

    @if (Auth::user()->hasPermission('read-engineers'))
        <option value="{{ route('admin.engineers.index', ['branch_id' => $query->id]) }}">{{ __('trans.branch engineers') }}</option>
    @endif

    @if (Auth::user()->hasPermission('read-moneySafe'))
        <option value="{{ route('admin.moneySafe.index', ['branch_id' => $query->id]) }}">{{ __('trans.money safe') }}</option>
    @endif

    @if (Auth::user()->hasPermission('read-bank'))
        <option value="{{ route('admin.bank.index', ['branch_id' => $query->id]) }}">{{ __('trans.bank') }}</option>
    @endif
</select>

@if (Auth::user()->hasPermission('update-branches'))
    <a href="{{ route('admin.branches.edit', $query->id) }}" class="btn btn-sm btn-primary">{{ __('trans.edit') }}</a>
@endif
@if (Auth::user()->hasPermission('delete-branches'))
    {!! Form::open(['route' => ['admin.branches.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()">{{ __('trans.delete') }}</button>
    {!! Form::close() !!}
@endif
