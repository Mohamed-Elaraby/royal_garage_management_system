@php
$pageType = __('trans.create');
$pageItem = __('trans.client')

@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')
    <div class="row">
        <div class="col-xs-6 center-block" style="float: none">
            <div class="card card-success">
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
                    {!! Form::open(['route' => 'admin.clients.store', 'method' => 'post']) !!}
                        <div class="form-group">
                            {!! Form::label('name', __('trans.client name'), ['class' => 'control-label']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('phone', __('trans.phone'), ['class' => 'control-label']) !!}
                            {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('building_number', __('trans.building number'), ['class' => 'control-label']) !!}
                            {!! Form::text('building_number', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('street_name', __('trans.street name'), ['class' => 'control-label']) !!}
                            {!! Form::text('street_name', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('district', __('trans.district'), ['class' => 'control-label']) !!}
                            {!! Form::text('district', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('city', __('trans.city'), ['class' => 'control-label']) !!}
                            {!! Form::text('city', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('country', __('trans.country'), ['class' => 'control-label']) !!}
                            {!! Form::text('country', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('postal_code', __('trans.postal code'), ['class' => 'control-label']) !!}
                            {!! Form::text('postal_code', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('vat_number', __('trans.vat number'), ['class' => 'control-label']) !!}
                            {!! Form::text('vat_number', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('employer', __('trans.employer'), ['class' => 'control-label']) !!}
                            {!! Form::text('employer', null, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('how_you_now_us', __('trans.how you now us'), ['class' => 'control-label']) !!}
                            {!! Form::text('how_you_now_us', null, [ 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                {!! Form::label('other_car', __('trans.other car'), ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-xs-6">
                                <label>
                                    {!! Form::radio('other_car_status', '0', true,  ['id' => 'other_car_status']) !!}
                                    لا
                                </label>
                                <label>
                                    {!! Form::radio('other_car_status', '1', null,  ['id' => 'other_car_status']) !!}
                                    نعم
                                </label>
                            </div>
                        </div>
                        {!! Form::text('other_car', null, [ 'class' => 'form-control', 'id' => 'other_car', 'disabled', 'placeholder' => __('trans.car type')]) !!}
                    </div>
                    </div>
                    <div class="form-group">
                            {!! Form::submit($pageType, ['class' => 'form-control btn btn-success']) !!}
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $("input[name='other_car_status']").change(function(e){
            if($(this).val() == 0) {
                $("#other_car").prop('disabled', true);
                $("#other_car").css('background-color', '#EBEBE4');
            } else if($(this).val() == 1) {
                $("#other_car").prop('disabled', false);
                $("#other_car").css('background-color', '#FFFFFF');
            }
        });
    </script>
@endpush
