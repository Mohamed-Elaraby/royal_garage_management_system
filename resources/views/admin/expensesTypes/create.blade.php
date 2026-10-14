@php
$pageType = __('trans.create');
$pageItem = __('trans.expenses type')

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
                    <h3 class="text-center"><i class="fa fa-user-secret"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'admin.expensesTypes.store', 'method' => 'post']) !!}
                    <div class="form-group">
                        {!! Form::label('name', __('trans.expenses type name'), ['class' => 'control-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
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

@push('links')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
@endpush

@push('scripts')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

    <script>
        $("#expenses_date").datepicker().datepicker("setDate", new Date()); // set datepicker
    </script>
@endpush
