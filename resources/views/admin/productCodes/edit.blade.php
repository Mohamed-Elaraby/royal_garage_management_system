@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.product code')
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
                    <h3 class="text-center"><i class="fa fa-briefcase"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => ['admin.productCodes.update', $productCode -> id], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('code', __('trans.product code'), ['class' => 'control-label']) !!}
                        {!! Form::text('code', $productCode -> code, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('name', __('trans.product name'), ['class' => 'control-label']) !!}
                        {!! Form::text('name', $productCode -> name, ['class' => 'form-control']) !!}
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
