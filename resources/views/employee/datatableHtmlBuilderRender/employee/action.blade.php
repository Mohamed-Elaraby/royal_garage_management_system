@if (Auth::user()->hasPermission('read-employees'))
    <a href="{{ route('employee.employees.show', $query->id) }}" class="btn btn-sm btn-info"> <i class="fa fa-eye"></i> {{ __('trans.details data for employee') }}</a>
@endif

@if (Auth::user()->hasPermission('update-employees'))
    <a href="{{ route('employee.employees.edit', $query->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> {{ __('trans.edit') }}</a>
@endif

@if (Auth::user()->hasPermission('delete-employees'))
    {!! Form::open(['route' => ['employee.employees.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
    {!! Form::close() !!}
@endif

<a href="{{ route('employee.salaries.details', $query->id) }}" class="btn btn-sm btn-success" title="تفاصيل الراتب"> <i class="fa fa-money"></i> {{ __('trans.salary details') }}</a>


<select name="" id="selectAction" class="form-control">
    <option value="" disabled selected>{{ __('trans.action') }}</option>
{{--    <option value="{{ route('employee.advances.create', ['employee_id' => $query->id]) }}">جميع سلف الموظف {{ $query->name }}</option>--}}
{{--    <option value="{{ route('employee.rewards.create', ['employee_id' => $query->id]) }}"> جميع مكافات الموظف {{ $query->name }}</option>--}}
{{--    <option value="{{ route('employee.discounts.create', ['employee_id' => $query->id]) }}">جميع خصومات الموظف {{ $query->name }}</option>--}}
{{--    <option value="{{ route('employee.vacations.create', ['employee_id' => $query->id]) }}">--}}
{{--    --}}

    <option value="{{ route('employee.advances.index', ['employee_id' => $query->id]) }}">جميع سلف الموظف {{ $query->name }}</option>
    <option value="{{ route('employee.rewards.index', ['employee_id' => $query->id]) }}"> جميع مكافات الموظف {{ $query->name }}</option>
    <option value="{{ route('employee.discounts.index', ['employee_id' => $query->id]) }}">جميع خصومات الموظف {{ $query->name }}</option>
    <option value="{{ route('employee.vacations.index', ['employee_id' => $query->id]) }}">جميع اجازات الموظف {{ $query->name }}</option>
</select>
