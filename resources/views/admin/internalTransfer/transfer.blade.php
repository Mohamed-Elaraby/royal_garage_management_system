@php
    $pageType = __('trans.transfer');
    $pageItem = __('trans.product')

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
                    <h3 class="text-center"><i class="fa fa-cart-arrow-down"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'admin.product.transfer.store', 'method' => 'post']) !!}
                    <div class="form-group">
                        {!! Form::label('from_branch', __('trans.from_branch'), ['class' => 'control-label']) !!}
                        {!! Form::select('from_branch', $branche, null,['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('to_branch', __('trans.to_branch'), ['class' => 'control-label']) !!}
                        {!! Form::select('to_branch', ['' => 'اختر الفرع المراد تحويل الصنف عليه']+$branches_without_one, null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('code', __('trans.product code'), ['class' => 'control-label']) !!}
                        {!! Form::text('code', $product -> code, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('name', __('trans.product name'), ['class' => 'control-label']) !!}
                        {!! Form::text('name', $product -> name, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('price', __('trans.purchasing price'), ['class' => 'control-label']) !!}
                        {!! Form::text('price', $product -> price, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('discount', __('trans.discount'), ['class' => 'control-label']) !!}
                        {!! Form::text('discount', $product -> discount, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('discount_type', __('trans.discount type'), ['class' => 'control-label']) !!}
                        {!! Form::text('discount_type', $product -> discount_type, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('discount_amount', __('trans.discount amount'), ['class' => 'control-label']) !!}
                        {!! Form::text('discount_amount', $product -> discount_amount, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('price_after_discount', __('trans.price after discount'), ['class' => 'control-label']) !!}
                        {!! Form::text('price_after_discount', $product -> price_after_discount, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('quantity', __('trans.quantity'), ['class' => 'control-label']) !!}
                        {!! Form::number('quantity', $product -> quantity, ['class' => 'form-control', 'max' => $product -> quantity, 'min' => 1]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::hidden('sub_category_id', $product -> sub_category_id) !!}
                    </div>
                    <input type="hidden" name="user_id" value="{{ $product -> user_id }}">
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

@endpush
