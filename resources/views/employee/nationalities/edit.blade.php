@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.nationality')
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
                    <h3 class="text-center"><i class="fa fa-child"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => ['employee.nationalities.update', $nationality -> id], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('nationality', __('trans.nationality'), ['class' => 'control-label']) !!}
                        {!! Form::text('nationality', $nationality -> nationality, ['class' => 'form-control']) !!}
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
