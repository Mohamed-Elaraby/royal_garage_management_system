@php
    $pageType = __('trans.create');
    $pageItem = __('trans.engineer')

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
                    <h3 class="text-center"><i class="fa fa-child"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'admin.engineers.store', 'method' => 'post']) !!}
                    <div class="form-group">
                        {!! Form::label('name', __('trans.engineer name'), ['class' => 'control-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('branch_id', __('trans.branch name'), ['class' => 'control-label']) !!}
                        {!! Form::select('branch_id', $branches, null, ['class' => 'form-control', 'placeholder' => __('trans.branch name')]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::submit($pageType, ['class' => 'form-control btn btn-success']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
