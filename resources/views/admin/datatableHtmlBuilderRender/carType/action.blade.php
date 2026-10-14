{{--<div class="btn-group convert_to_display_flex">--}}
{{--    <button type="button" class="btn btn-warning">{{ __('trans.action') }}</button>--}}
{{--    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">--}}
{{--        <span class="caret"></span>--}}
{{--        <span class="sr-only">Toggle Dropdown</span>--}}
{{--    </button>--}}
{{--    <ul class="dropdown-menu button_custom_style" role="menu" style="background-color: #ffa500;">--}}
{{--        @if (Auth::user()->hasPermission('create-carSize'))--}}
{{--            <li><a href="{{ route('admin.carSize.create', ['car_type_id' => $query->id]) }}" >{{ __('trans.create car size') }}</a></li>--}}
{{--        @endif--}}
{{--        @if (Auth::user()->hasPermission('read-carSize'))--}}
{{--            <li><a href="{{ route('admin.carSize.index', ['car_type_id' => $query->id]) }}" >{{ __('trans.show all sizes related by type') . ' ' .$query->name}}</a></li>--}}
{{--        @endif--}}
{{--    </ul>--}}
{{--</div>--}}


<select id="selectAction" class="form-control">
    <option value="" disabled selected>{{ __('trans.car size') }}</option>
    @if (Auth::user()->hasPermission('create-carSize'))
        <option value="{{ route('admin.carSize.create', ['car_type_id' => $query->id]) }}" >{{ __('trans.create car size') }}</option>
    @endif
    @if (Auth::user()->hasPermission('read-carSize'))
        <option value="{{ route('admin.carSize.index', ['car_type_id' => $query->id]) }}" >{{ __('trans.show all sizes related by type') . ' ' .$query->name}}</option>
    @endif
</select>


@if (Auth::user()->hasPermission('update-carType'))
    <a href="{{ route('admin.carType.edit', $query->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> {{ __('trans.edit') }}</a>
@endif
@if (Auth::user()->hasPermission('delete-carType'))
    {!! Form::open(['route' => ['admin.carType.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"> <i class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
    {!! Form::close() !!}
@endif
