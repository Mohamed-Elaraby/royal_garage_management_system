@php
    $pageType = __('trans.create');
    $pageItem = __('trans.vacation')

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
                    {!! Form::open(['route' => 'employee.vacations.store', 'method' => 'post']) !!}
                    {!! Form::hidden('employee_id', $employee -> id) !!}
                    <div class="form-group">
                        {!! Form::label('start_vacation', __('trans.start date of vacation'), ['class' => 'control-label']) !!}
                        {!! Form::text('start_vacation', null, ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('end_vacation', __('trans.end date of vacation'), ['class' => 'control-label']) !!}
                        {!! Form::text('end_vacation', null, ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd']) !!}
                    </div>
                    <div id="type_group">
                        <div class="form-group">
                            {!! Form::label('type', __('trans.type'), ['class' => 'control-label']) !!}
                            {!! Form::select('type', ['' => '__ اختر نوع الاجازة __','مدفوعة الاجر' => 'مدفوعة الاجر', 'تخصم من الراتب' => 'تخصم من الراتب'] , null , ['class' => 'form-control', 'id' => 'type']) !!}
                        </div>
                        <div id="payment_schedule_group"></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes', __('trans.notes'), ['class' => 'control-label']) !!}
                        {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
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
@push('links')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

@endpush
@push('scripts')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $("#start_vacation, #end_vacation").datepicker().datepicker("setDate", new Date()); // set datepicker
    </script>
@endpush
