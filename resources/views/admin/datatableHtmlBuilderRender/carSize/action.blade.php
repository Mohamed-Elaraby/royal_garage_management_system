{{--<div class="btn-group convert_to_display_flex">--}}
{{--    <button type="button" class="btn btn-info">{{ __('trans.engine number') }}</button>--}}
{{--    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">--}}
{{--        <span class="caret"></span>--}}
{{--        <span class="sr-only">Toggle Dropdown</span>--}}
{{--    </button>--}}
{{--    <ul class="dropdown-menu button_custom_style" role="menu" style="background-color: #00c0ef;">--}}
{{--        @if (Auth::user()->hasPermission('create-carEngine'))--}}
{{--            <li>--}}
{{--                <a href="{{ route('admin.carEngine.create', ['car_size_id' => $query->id]) }}">{{ __('trans.create car engine') }}</a>--}}
{{--            </li>--}}
{{--        @endif--}}
{{--        @if (Auth::user()->hasPermission('read-carModel'))--}}
{{--            <li>--}}
{{--                <a href="{{ route('admin.carEngine.index', ['car_size_id' => $query->id]) }}">{{ __('trans.all car engine') }}</a>--}}
{{--            </li>--}}
{{--        @endif--}}
{{--    </ul>--}}
{{--</div>--}}



{{--<div class="btn-group convert_to_display_flex">--}}
{{--    <button type="button" class="btn btn-warning">{{ __('trans.car development code') }}</button>--}}
{{--    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">--}}
{{--        <span class="caret"></span>--}}
{{--        <span class="sr-only">Toggle Dropdown</span>--}}
{{--    </button>--}}
{{--    <ul class="dropdown-menu button_custom_style" role="menu" style="background-color: #ffa500;">--}}
{{--        @if (Auth::user()->hasPermission('create-carDevelopmentCode'))--}}
{{--            <li>--}}
{{--                <a href="{{ route('admin.carDevelopmentCode.create', ['car_size_id' => $query->id]) }}">{{ __('trans.create car development code') }}</a>--}}
{{--            </li>--}}
{{--        @endif--}}
{{--        @if (Auth::user()->hasPermission('read-carDevelopmentCode'))--}}
{{--            <li>--}}
{{--                <a href="{{ route('admin.carDevelopmentCode.index', ['car_size_id' => $query->id]) }}">{{ __('trans.all car development code') }}</a>--}}
{{--            </li>--}}
{{--        @endif--}}
{{--    </ul>--}}
{{--</div>--}}


<select class="form-control selectAction">
    <option value="" disabled selected>{{ __('trans.engine number') }}</option>
    @if (Auth::user()->hasPermission('create-carEngine'))
        <option value="{{ route('admin.carEngine.create', ['car_size_id' => $query->id]) }}">{{ __('trans.create car engine') }}</option>
    @endif

    @if (Auth::user()->hasPermission('read-carModel'))
        <option value="{{ route('admin.carEngine.index', ['car_size_id' => $query->id]) }}">{{ __('trans.all car engine') }}</option>
    @endif
</select>


<select class="form-control selectAction">
    <option value="" disabled selected>{{ __('trans.car development code') }}</option>
    @if (Auth::user()->hasPermission('create-carDevelopmentCode'))

        <option value="{{ route('admin.carDevelopmentCode.create', ['car_size_id' => $query->id]) }}">{{ __('trans.create car development code') }}</option>

    @endif
    @if (Auth::user()->hasPermission('read-carDevelopmentCode'))

        <option value="{{ route('admin.carDevelopmentCode.index', ['car_size_id' => $query->id]) }}">{{ __('trans.all car development code') }}</option>

    @endif
</select>
@if (Auth::user()->hasPermission('update-carSize'))
    <a href="{{ route('admin.carSize.edit', $query->id) }}" class="btn btn-sm btn-primary"> <i
            class="fa fa-edit"></i> {{ __('trans.edit') }}</a>
@endif
@if (Auth::user()->hasPermission('delete-carSize'))
    {!! Form::open(['route' => ['admin.carSize.destroy', $query->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
    <button class="btn btn-sm btn-danger" onclick="return showDeleteMessage()"><i
            class="fa fa-remove"></i> {{ __('trans.delete') }}</button>
    {!! Form::close() !!}
@endif


