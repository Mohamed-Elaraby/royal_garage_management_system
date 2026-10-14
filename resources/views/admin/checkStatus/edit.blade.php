@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.checkStatus')
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
                    {!! Form::open(['route' => ['admin.checkStatus.update', $checkStatus -> id], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('name', __('trans.checkStatus'), ['class' => 'control-label']) !!}
                        @php($noEditName = $checkStatus -> name == 'تم تسليم السيارة الى العميل'? 'disabled': '')
                        {!! Form::text('name', $checkStatus -> name, ['class' => 'form-control', $noEditName]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('color', __('trans.color'), ['class' => 'control-label']) !!}
                        <input type="color" name="color" id="color" value="{{ $checkStatus -> color }}">
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

