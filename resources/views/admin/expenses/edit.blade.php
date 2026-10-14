@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.expenses')
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
                    {!! Form::open(['route' => ['admin.expenses.update', $expenses -> id], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('amount', __('trans.amount'), ['class' => 'control-label']) !!}
                        {!! Form::text('amount', $expenses -> amount, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('expenses_type_id', __('trans.expenses type'), ['class' => 'control-label']) !!}
                        {!! Form::select('expenses_type_id',$expensesTypes , array_key_exists($expenses -> expenses_type_id, $expensesTypes)?$expenses -> expenses_type_id:'' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {{--                        @php($branch_id = auth()->user()->branch -> id?? '')--}}
                        {!! Form::label('branch_id', __('trans.branch'), ['class' => 'control-label']) !!}
                        {!! Form::select('branch_id', $branches, array_key_exists($expenses -> branch_id, $branches)?$expenses -> branch_id:'', [ 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('expenses_date', __('trans.expenses date'), ['class' => 'control-label']) !!}
                        {!! Form::text('expenses_date', $expenses -> expenses_date, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes', __('trans.notes'), ['class' => 'control-label']) !!}
                        {!! Form::text('notes', $expenses -> notes, [ 'class' => 'form-control']) !!}
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
