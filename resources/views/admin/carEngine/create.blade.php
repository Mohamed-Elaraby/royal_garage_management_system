@php
    $pageType = __('trans.create');
    $pageItem = __('trans.engine number')
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
                    <h3 class="text-center"><i class="fa fa-car"></i> {{ $pageType .' '. request('car_size_id')?__('trans.car size'). '['.$target_car_size -> name.']': __('trans.car size') }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'admin.carEngine.store', 'method' => 'post']) !!}
                    {!! Form::hidden('car_size_id', $target_car_size -> id) !!}
                    <div class="form-group">
                        {!! Form::label('name', __('trans.engine number'), ['class' => 'control-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
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
