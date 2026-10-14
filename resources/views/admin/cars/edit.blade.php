@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.car')
@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')
    <div class="row">
        <div class="col-xs-6 center-block" style="float: none">
            <div class="card card-primary mt-5">
                <div class="error_messages">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-header">
                    <h3 class="text-center"><i class="fa fa-user-secret"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => ['admin.cars.update', $car -> id], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('car_type_id', __('trans.car type'), ['class' => 'control-label']) !!}
                        {!! Form::select('car_type_id', $carTypes, array_key_exists($car -> car_type_id, $carTypes)? $car -> car_type_id: '', ['class' => 'form-control', 'placeholder' => __('trans.car type')]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('car_size_id', __('trans.car size'), ['class' => 'control-label']) !!}
                        <select class="form-control" name="car_size_id" id="car_size_id">
                            <option value="">{{ __('trans.car size') }}</option>
                            @foreach($carSizes as $size)
                                <option {{ $size -> id == $car -> car_size_id ? 'selected': '' }} value="{{ $size -> id }}">{{ $size -> name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        {!! Form::label('car_engine_id', __('trans.engine number'), ['class' => 'control-label']) !!}
                        <select class="form-control" name="car_engine_id" id="car_engine_id">
                            <option value="">{{ __('trans.engine number') }}</option>
                            @foreach($carEngines as $engine)
                                <option {{ $engine -> id == $car -> car_engine_id ? 'selected': '' }} value="{{ $engine -> id }}">{{ $engine -> name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        {!! Form::label('car_development_code_id', __('trans.car development code'), ['class' => 'control-label']) !!}
                        <select class="form-control" name="car_development_code_id" id="car_development_code_id">
                            <option value="">{{ __('trans.car development code') }}</option>
                            @foreach($carDevelopmentCodes as $developmentCode)
                                <option {{ $developmentCode -> id == $car -> car_development_code_id ? 'selected': '' }} value="{{ $developmentCode -> id }}">{{ $developmentCode -> name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        {!! Form::label('car_model_id', __('trans.model'), ['class' => 'control-label']) !!}
                        {!! Form::select('car_model_id', $carModels, array_key_exists($car ->car_model_id, $carModels)? $car ->car_model_id: '', [ 'class' => 'form-control', 'placeholder' => __('trans.model')]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('chassis_number', __('trans.chassis number'), ['class' => 'control-label']) !!}
                        {!! Form::text('chassis_number', $car ->chassis_number, [ 'class' => 'form-control', 'placeholder' => __('trans.chassis number')]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('plate_number', __('trans.plate number'), ['class' => 'control-label']) !!}
                        {!! Form::text('plate_number', $car ->plate_number, [ 'class' => 'form-control', 'placeholder' => __('trans.plate number')]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('car_color', __('trans.car color'), ['class' => 'control-label']) !!}
                        {!! Form::text('car_color', $car ->car_color, [ 'class' => 'form-control', 'placeholder' => __('trans.car color')]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::submit($pageType, ['class' => 'form-control btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('scripts')
    <script>
        $(document).ready(function () {
            $('select[name=car_type_id]').on('change', function () {
                let car_type_id = $(this).children(':selected').val();
                let car_size_element = $('#car_size_id');
                let car_engine_element = $('#car_engine_id');
                let car_development_code_element = $('#car_development_code_id');
                if (car_type_id == ''){
                    car_size_element.html('<option value="">'+'{{ __('trans.car size') }}'+'</option>');
                    car_size_element.prop('disabled', true);

                    car_engine_element.html('<option value="">'+'{{ __('trans.engine number') }}'+'</option>');
                    car_engine_element.prop('disabled', true);

                    car_development_code_element.html('<option value="">'+'{{ __('trans.car development code') }}'+'</option>');
                    car_development_code_element.prop('disabled', true);
                }else {
                    car_size_element.prop('disabled', false);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('admin.car.getCarSizesByAjax') }}',
                        method: 'POST',
                        data: {car_type_id:car_type_id},
                        success: function (data) {
                            if (!data.error)
                            {
                                car_size_element.empty();
                                car_size_element.append('<option value="">'+'{{ __('trans.car size') }}'+'</option>');
                                $.each(data, function (index, element){
                                    let value = '';
                                    if (element.id == {{ $car ->car_size_id }})
                                        value = 'selected';
                                    car_size_element.append('<option '+value+' value="'+element.id+'">'+element.name+'</option>');
                                })
                            }
                        }
                    })
                }
            })
        })
    </script>

    <script>
        $(document).ready(function () {
            $('select[name=car_size_id]').on('change', function () {
                let car_size_id = $(this).children(':selected').val();
                let car_engine_element = $('#car_engine_id');
                let car_development_code_element = $('#car_development_code_id');
                if (car_size_id == ''){
                    car_engine_element.html('<option value="">'+'{{ __('trans.engine number') }}'+'</option>');
                    car_engine_element.prop('disabled', true);

                    car_development_code_element.html('<option value="">'+'{{ __('trans.car development code') }}'+'</option>');
                    car_development_code_element.prop('disabled', true);
                }else {
                    car_engine_element.prop('disabled', false);
                    car_development_code_element.prop('disabled', false);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('admin.car.getCarDevCodeAndEnginesByAjax') }}',
                        method: 'POST',
                        data: {car_size_id},
                        success: function (data) {
                            if (!data.error)
                            {
                                car_engine_element.empty();
                                $.each(data.carEngines, function (index, element){
                                    let value = '';
                                    if (element.id == {{ $car ->car_engine_id }})
                                        value = 'selected';
                                    car_engine_element.append('<option '+ value +' value="'+element.id+'">'+element.name+'</option>');
                                })

                                car_development_code_element.empty();
                                $.each(data.carDevelopmentCodes, function (index, element){
                                    let value = '';
                                    if (element.id == {{ $car ->car_development_code_id }})
                                        value = 'selected';
                                    car_development_code_element.append('<option '+ value +' value="'+element.id+'">'+element.name+'</option>');
                                })
                            }
                        }
                    })
                }
            })
        })
    </script>
@endpush
