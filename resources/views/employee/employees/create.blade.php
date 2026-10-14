@php
    $pageType = __('trans.create');
    $pageItem = __('trans.employee')

@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')
    <div class="row">
        <div class="col-xs-8 center-block" style="float: none">
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
                    {!! Form::open(['route' => 'employee.employees.store', 'method' => 'post']) !!}
                    <div class="form-group">
                        {!! Form::label('name', __('trans.employee name'), ['class' => 'control-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('birth_date', __('trans.birth date'), ['class' => 'control-label']) !!}
                        {!! Form::text('birth_date', null, ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('date_of_hiring', __('trans.date of hiring'), ['class' => 'control-label']) !!}
                        {!! Form::text('date_of_hiring', null, ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('job_title_id', __('trans.job title'), ['class' => 'control-label']) !!}
                        {!! Form::select('job_title_id', $jobTitles, null, ['class' => 'form-control', 'placeholder' => __('trans.job title')]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('id_number', __('trans.id number'), ['class' => 'control-label']) !!}
                        {!! Form::text('id_number', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('passport_number', __('trans.passport number'), ['class' => 'control-label']) !!}
                        {!! Form::text('passport_number', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('branch_id', __('trans.branch'), ['class' => 'control-label']) !!}
                        {!! Form::select('branch_id', $branches, null, ['class' => 'form-control', 'placeholder' => __('trans.branch name')]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('nationality_id', __('trans.nationality'), ['class' => 'control-label']) !!}
                        {!! Form::select('nationality_id', $nationalities, null, ['class' => 'form-control', 'placeholder' => __('trans.nationality')]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('salary', __('trans.salary details'), ['class' => 'control-label']) !!}
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('main', __('trans.main'), ['class' => 'control-label']) !!}
                                {!! Form::text('main', 1500, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('housing_allowance', __('trans.housing allowance'), ['class' => 'control-label']) !!}
                                {!! Form::text('housing_allowance', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('transfer_allowance', __('trans.transfer allowance'), ['class' => 'control-label']) !!}
                                {!! Form::text('transfer_allowance', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('travel_allowance', __('trans.travel allowance'), ['class' => 'control-label']) !!}
                                {!! Form::text('travel_allowance', null, ['class' => 'form-control']) !!}
                            </div>
{{--                            <div class="col-md-4">--}}
{{--                                {!! Form::label('end_service_allowance', __('trans.end service allowance'), ['class' => 'control-label']) !!}--}}
{{--                                {!! Form::text('end_service_allowance', null, ['class' => 'form-control']) !!}--}}
{{--                            </div>--}}
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        {!! Form::label('other_allowance', __('trans.other allowance'), ['class' => 'control-label']) !!}
                                    </div>
                                    <div class="col-md-8">
                                        <label>
                                            {!! Form::checkbox('active_other_allowance', null, null,  ['id' => 'active_other_allowance']) !!}
                                            <span>تفعيل</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::label('other_allowance', __('trans.amount'), ['class' => 'control-label']) !!}
                                        {!! Form::text('other_allowance', null, ['class' => 'form-control', 'disabled']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('description_of_other_allowance', __('trans.details'), ['class' => 'control-label']) !!}
                                        {!! Form::text('description_of_other_allowance', null, ['class' => 'form-control', 'disabled']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
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
        $("#birth_date, #date_of_hiring, #date_of_leaving_work").datepicker().datepicker("setDate", new Date()); // set datepicker

        $('#active_other_allowance').on("change", function() {
            if($(this).is(':checked')) {
                $(this).next('span').text('تعطيل');
                $('#other_allowance').prop('disabled', false)
                $('#description_of_other_allowance').prop('disabled', false)

            }else
            {
                $(this).next('span').text('تفعيل');
                $('#other_allowance').prop('disabled', true)
                $('#description_of_other_allowance').prop('disabled', true)
            }

        });
    </script>
@endpush
