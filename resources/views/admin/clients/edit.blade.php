@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.client')
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
                    {!! Form::open(['route' => ['admin.clients.update', $client -> id], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('name', __('trans.client name'), ['class' => 'control-label']) !!}
                        {!! Form::text('name', $client -> name, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone', __('trans.phone'), ['class' => 'control-label']) !!}
                        {!! Form::text('phone', $client -> phone, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('building_number', __('trans.building number'), ['class' => 'control-label']) !!}
                        {!! Form::text('building_number', $client -> building_number, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('street_name', __('trans.street name'), ['class' => 'control-label']) !!}
                        {!! Form::text('street_name', $client -> street_name, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('district', __('trans.district'), ['class' => 'control-label']) !!}
                        {!! Form::text('district', $client -> district, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('city', __('trans.city'), ['class' => 'control-label']) !!}
                        {!! Form::text('city', $client -> city, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('country', __('trans.country'), ['class' => 'control-label']) !!}
                        {!! Form::text('country', $client -> country, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('postal_code', __('trans.postal code'), ['class' => 'control-label']) !!}
                        {!! Form::text('postal_code', $client -> postal_code, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('vat_number', __('trans.vat number'), ['class' => 'control-label']) !!}
                        {!! Form::text('vat_number', $client -> vat_number, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('employer', __('trans.employer'), ['class' => 'control-label']) !!}
                        {!! Form::text('employer', $client -> employer, [ 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('how_you_now_us', __('trans.how you now us'), ['class' => 'control-label']) !!}
                        {!! Form::text('how_you_now_us', $client -> how_you_now_us, [ 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('other_car', __('trans.other car'), ['class' => 'control-label']) !!}
                        {!! Form::text('other_car', $client -> other_car, [ 'class' => 'form-control']) !!}
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
