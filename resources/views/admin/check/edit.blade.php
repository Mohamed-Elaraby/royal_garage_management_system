@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.check')
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
                    <h3 class="text-center"><i class="fa fa-user"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => ['admin.check.update', $check -> id], 'method' => 'put', 'files'=> true]) !!}
                        <div class="form-group">
                            {!! Form::label('counter_number', __('trans.counter number'), ['class' => 'control-label']) !!}
                            {!! Form::text('counter_number', $check -> counter_number, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('chassis number', __('trans.chassis number'), ['class' => 'control-label']) !!}
                            {!! Form::text('chassis number', $check -> chassis_number, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('plate number', __('trans.plate number'), ['class' => 'control-label']) !!}
                            {!! Form::text('plate number', $check -> plate_number, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_type', __('trans.car type'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_type', $check -> car_type, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('car_model', __('trans.model'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_model', $check -> car_model, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('car_size', __('trans.car size'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_size', $check -> car_size, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('car_engine', __('trans.engine number'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_engine', $check -> car_engine, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('car_development_code', __('trans.car development code'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_development_code', $check -> car_development_code, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('car color', __('trans.car color'), ['class' => 'control-label']) !!}
                            {!! Form::text('car color', $check -> car_color, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-3">
                                    {!! Form::label('fuel_level', __('trans.fuel level'), ['class' => 'control-label']) !!}
                                </div>
                                <div class="col-xs-9">
                                    <input name="fuel_level" type="range" value="{{ $check -> fuel_level }}" min="0" max="100"
                                           oninput="this.nextElementSibling.value = this.value+'%'">
                                    <output>{{ $check -> fuel_level }}%</output>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('driver_name', __('trans.driver name'), ['class' => 'control-label']) !!}
                            {!! Form::text('driver_name', $check -> driver_name, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_status_report', __('trans.repairs needed'), ['class' => 'control-label']) !!}
                            {!! Form::textarea('car_status_report', $check -> getOriginal('car_status_report'), ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('engineer_id', __('trans.engineer name'), ['class' => 'control-label']) !!}
                            {!! Form::select('engineer_id', $engineers, array_key_exists($check -> engineer_id, $engineers)?$check -> engineer_id:'', [ 'class' => 'form-control', 'placeholder' => __('trans.engineer name')]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('technical_id', __('trans.technical name'), ['class' => 'control-label']) !!}
                            @foreach ($technicals as $technical)
                                <div>
                                    <label>
                                        {!! Form::checkbox('technical_id[]', $technical -> id, null, [array_key_exists($technical -> id, $check_technicals)? 'checked': ''] ) !!}
                                        {{ $technical -> name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_status_report_note', __('trans.notes when entering the car'), ['class' => 'control-label']) !!}
                            {!! Form::textarea('car_status_report_note', $check -> getOriginal('car_status_report_note'), ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_images_note', __('trans.car images note'), ['class' => 'control-label']) !!}
                            {!! Form::textarea('car_images_note', $check -> getOriginal('car_images_note'), ['class' => 'form-control']) !!}
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

@endpush
@push('scripts')

@endpush
