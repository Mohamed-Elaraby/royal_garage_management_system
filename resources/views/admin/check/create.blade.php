@php
$pageType = __('trans.create');
$pageItem = __('trans.check')
@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')

    <!-- Multi step form -->
    <div id="multiStepForm" class="multiStepForm">

        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 form-box">
                    <!-- Open Form -->
                {!! Form::open(['route' => 'admin.check.store', 'method' => 'post', 'files' => true, 'class' => 'f1', 'role'=>'form']) !!}
                {!! Form::hidden('client_id', $client_id) !!}
                {!! Form::hidden('car_id', $car_id) !!}
                @if ($branch_id != '') {!! Form::hidden('branch_id', $branch_id) !!} @endif
                    <h2 style="background-color: #3C8DBC; color: #fff">{{ __('trans.Royal Garage group') }}</h2>
                    <p>{{ __('trans.create check') }}</p>
                    <div class="f1-steps">
                        <div class="f1-progress">
                            <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3" style="width: 16.66%;"></div>
                        </div>
                        <div class="f1-step active">
                            <div class="f1-step-icon"><i class="fa fa-car"></i></div>
                            <p>{{ __('trans.car data') }}</p>
                        </div>
                        <div class="f1-step">
                            <div class="f1-step-icon"><i class="fa fa-wrench"></i></div>
                            <p>{{ __('trans.repairs needed') }}</p>
                        </div>
                        <div class="f1-step ">
                            <div class="f1-step-icon"><i class="fa fa-upload"></i></div>
                            <p>{{ __('trans.car images') }}</p>
                        </div>
                    </div>

                    <fieldset>
                        <h4 class="text-center">{{ __('trans.car data') }}</h4>
                        <div class="form-group">
                            {!! Form::label('counter_number', __('trans.counter number'), ['class' => 'control-label']) !!}
                            {!! Form::text('counter_number', null, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('chassis_number', __('trans.chassis number'), ['class' => 'control-label']) !!}
                            {!! Form::text('chassis_number', $targetCar -> chassis_number, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('plate_number', __('trans.plate number'), ['class' => 'control-label']) !!}
                            {!! Form::text('plate_number', $targetCar -> plate_number, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_type', __('trans.car type'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_type', $targetCar -> carType -> name, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_size', __('trans.car size'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_size', $targetCar -> carSize -> name, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_engine', __('trans.engine number'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_engine', $targetCar -> carEngine -> name ?? '', [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_development_code', __('trans.car development code'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_development_code', $targetCar -> carDevelopmentCode -> name ?? null, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_model', __('trans.model'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_model', $targetCar -> carModel -> name, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_color', __('trans.car color'), ['class' => 'control-label']) !!}
                            {!! Form::text('car_color', $targetCar -> car_color, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-3">
                                    {!! Form::label('fuel_level', __('trans.fuel level'), ['class' => 'control-label']) !!}
                                </div>
                                <div class="col-xs-9">
                                    <input name="fuel_level" type="range" value="50" min="0" max="100"
                                    oninput="this.nextElementSibling.value = this.value+'%'">
                                    <output>50%</output>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('driver_name', __('trans.driver name'), ['class' => 'control-label']) !!}
                            {!! Form::text('driver_name', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('engineer_id', __('trans.engineer name'), ['class' => 'control-label']) !!}
                            {!! Form::select('engineer_id', $engineers, null, [ 'class' => 'form-control', 'placeholder' => __('trans.engineer name')]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('technical_id', __('trans.technical name'), ['class' => 'control-label']) !!}
                            @foreach ($technicals as $technical)
                                <div>
                                    <label>
                                        {!! Form::checkbox('technical_id[]', $technical -> id, null) !!}
                                        {{ $technical -> name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="f1-buttons">
                            <button type="button" class="btn btn-next">{{ __('trans.next') }}</button>
                        </div>
                    </fieldset>

                    <fieldset>
                        <h4 class="text-center">{{ __('trans.repairs needed') }}</h4>
                        <div class="form-group">
                            {!! Form::label('car_status_report', __('trans.repairs needed'), ['class' => 'control-label']) !!}
                            {!! Form::textarea('car_status_report', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_status_report_note', __('trans.notes when entering the car'), ['class' => 'control-label']) !!}
                            {!! Form::textarea('car_status_report_note', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="f1-buttons">
                            <button type="button" class="btn btn-previous">{{ __('trans.previous') }}</button>
                            <button type="button" class="btn btn-next">{{ __('trans.next') }}</button>
                        </div>
                    </fieldset>

                    <fieldset>
                        <h4 class="text-center">{{ __('trans.car images') }}</h4>
                        <div class="form-group">
                            <label for="upload_button" class="upload_button">
                                <span> أختر الصور </span>
                                <i class="fa fa-upload fa-2x"></i>
                                {!! Form::file('car_images[]', ['multiple', 'id'=> 'upload_button']) !!}

                            </label>
                        </div>
                        <div class="form-group">
                            {!! Form::label('car_images_note', __('trans.car images note'), ['class' => 'control-label']) !!}
                            {!! Form::textarea('car_images_note', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="f1-buttons">
                            <button type="button" class="btn btn-previous">{{ __('trans.previous') }}</button>
                            <button type="submit" class="btn btn-submit">{{ __('trans.create') }}</button>
                        </div>
                    </fieldset>
                {!! Form::close() !!}
                <!-- Close Form -->
            </div>
        </div>
    </div>
    <!-- End Multi step form -->
@endsection
@push('links')
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/wizardForm/css/form-elements.css') }}">

    @if( LaravelLocalization::getCurrentLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/wizardForm/css/style_ar.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/wizardForm/css/style_en.css') }}">
    @endif
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom File Inputs -->
    <link rel="stylesheet" href="{{ asset('assets/wizardForm/css/customFileInput.css') }}">
@endpush
@push('scripts')
    <!-- Javascript -->

    <script src="{{ asset('assets/wizardForm/js/jquery.backstretch.min.js') }}"></script>
    <script src="{{ asset('assets/wizardForm/js/retina-1.1.0.min.js') }}"></script>
    <script src="{{ asset('assets/wizardForm/js/scripts.js') }}"></script>

    <!--[if lt IE 10]>
    <script src="{{ asset('assets/wizardForm/js/placeholder.js') }}"></script>
    <![endif]-->
    <!-- Custom File Inputs -->

@endpush
