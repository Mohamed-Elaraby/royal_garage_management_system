@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.reward')
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
                    {!! Form::open(['route' => ['employee.rewards.update', $reward -> id], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('amount', __('trans.amount'), ['class' => 'control-label']) !!}
                        {!! Form::text('amount', $reward -> amount, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('status', __('trans.status'), ['class' => 'control-label']) !!}
                        {!! Form::select('status', ['غير مضافة حتى الان' => 'تضاف الى الراتب', 'يحصل عليها العامل فورا' => 'يحصل عليها العامل فورا'] , $reward -> status , ['class' => 'form-control', 'id' => 'status']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes', __('trans.notes'), ['class' => 'control-label']) !!}
                        {!! Form::textarea('notes', $reward -> notes, ['class' => 'form-control']) !!}
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
