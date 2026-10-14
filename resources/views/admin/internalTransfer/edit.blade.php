@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.product')
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
                    <h3 class="text-center"><i class="fa fa-cart-arrow-down"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => ['admin.products.update', $product -> id], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('name', __('trans.product name'), ['class' => 'control-label']) !!}
                        {!! Form::text('name', $product -> name, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('price', __('trans.purchasing price'), ['class' => 'control-label']) !!}
                        {!! Form::text('price', $product -> price, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('selling_price', __('trans.selling price'), ['class' => 'control-label']) !!}
                        {!! Form::text('selling_price', $product -> selling_price, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('quantity', __('trans.quantity'), ['class' => 'control-label']) !!}
                        {!! Form::text('quantity', $product -> quantity, ['class' => 'form-control']) !!}
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
