@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.management notes')
@endphp
@extends('admin.layouts.app')

@section('title', __('trans.select branch'))

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
                    <h3 class="text-center"><i class="fa fa-user"></i> {{ __('trans.select branch') }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => ['admin.statement.index'], 'method' => 'get']) !!}
                        <div class="form-group">
                            {!! request('client_id') ? Form::hidden('client_id', request('client_id')) : '' !!}
                            {!! request('car_id') ? Form::hidden('car_id', request('car_id')) : '' !!}
                            {!! Form::label('branch_id', __('trans.branch name'), ['class' => 'control-label']) !!}
                            {!! Form::select('branch_id', $branches, null,  ['class' => 'form-control']) !!}
                        </div>
                    <div class="form-group">
                        {!! Form::submit(__('trans.continue'), ['class' => 'form-control btn btn-warning']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')

@endpush
@push('scripts')

@endpush
