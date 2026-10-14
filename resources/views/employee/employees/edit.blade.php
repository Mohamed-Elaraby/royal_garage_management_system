@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.employee')
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
                    {!! Form::open(['route' => ['employee.employees.update', $employee -> id], 'method' => 'put']) !!}
                    <div class="form-group">
                        {!! Form::label('name', __('trans.employee name'), ['class' => 'control-label']) !!}
                        {!! Form::text('name', $employee -> name, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('birth_date', __('trans.birth date'), ['class' => 'control-label']) !!}
                        {!! Form::text('birth_date', $employee -> birth_date, ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('date_of_hiring', __('trans.date of hiring'), ['class' => 'control-label']) !!}
                        {!! Form::text('date_of_hiring', $employee -> date_of_hiring, ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('job_title_id', __('trans.job title'), ['class' => 'control-label']) !!}
                        {!! Form::select('job_title_id', $jobTitles, $employee ->job_title_id, ['class' => 'form-control', 'placeholder' => __('trans.job title')]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('id_number', __('trans.id number'), ['class' => 'control-label']) !!}
                        {!! Form::text('id_number', $employee -> id_number, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('passport_number', __('trans.passport number'), ['class' => 'control-label']) !!}
                        {!! Form::text('passport_number', $employee -> passport_number, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('branch_id', __('trans.branch'), ['class' => 'control-label']) !!}
                        {!! Form::select('branch_id', $branches, $employee ->branch_id, ['class' => 'form-control', 'placeholder' => __('trans.branch name')]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('nationality_id', __('trans.nationality'), ['class' => 'control-label']) !!}
                        {!! Form::select('nationality_id', $nationalities, $employee -> nationality_id, ['class' => 'form-control', 'placeholder' => __('trans.nationality')]) !!}
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                {!! Form::label('date_of_leaving_work', __('trans.date of leaving work'), ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-md-3">
                                <label>
                                    {!! Form::checkbox('active_date_leaving', null, null,  ['id' => 'active_date_leaving', $employee ->date_of_leaving_work || $salary -> end_service_allowance? '': 'checked']) !!}
                                    <span>{{ $employee ->date_of_leaving_work || $salary -> end_service_allowance? 'تعطيل': 'تفعيل' }}</span>
                                </label>
                            </div>
                        </div>
                        {!! Form::text('date_of_leaving_work', $employee ->date_of_leaving_work, ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd', $employee ->date_of_leaving_work? '': 'disabled']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('username', __('trans.username'), ['class' => 'control-label', '']) !!}
                        {!! Form::text('username', $employee -> username, ['class' => 'form-control', 'autocomplete' =>'off', 'disabled']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password', __('trans.reset password'), ['class' => 'control-label', '']) !!}
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('salary', __('trans.salary details'), ['class' => 'control-label']) !!}
                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('main', __('trans.main'), ['class' => 'control-label']) !!}
                                {!! Form::text('main', $salary -> main, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('housing_allowance', __('trans.housing allowance'), ['class' => 'control-label']) !!}
                                {!! Form::text('housing_allowance', $salary ->housing_allowance , ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('transfer_allowance', __('trans.transfer allowance'), ['class' => 'control-label']) !!}
                                {!! Form::text('transfer_allowance', $salary -> transfer_allowance, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('travel_allowance', __('trans.travel allowance'), ['class' => 'control-label']) !!}
                                {!! Form::text('travel_allowance', $salary -> travel_allowance, ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('end_service_allowance', __('trans.end service allowance'), ['class' => 'control-label']) !!}
                                {!! Form::text('end_service_allowance', $salary -> end_service_allowance, ['class' => 'form-control', 'id' => 'end_service_allowance', $employee ->date_of_leaving_work || $salary -> end_service_allowance? '': 'disabled']) !!}
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        {!! Form::label('other_allowance', __('trans.other allowance'), ['class' => 'control-label']) !!}
                                    </div>
                                    <div class="col-md-8">
                                        <label>
                                            {!! Form::checkbox('active_other_allowance', null, null,  ['id' => 'active_other_allowance', $salary -> other_allowance? '': 'checked']) !!}
                                            <span>{{ $salary -> other_allowance || $salary -> description_of_other_allowance? 'تعطيل': 'تفعيل' }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::label('other_allowance', __('trans.amount'), ['class' => 'control-label']) !!}
                                        {!! Form::text('other_allowance', $salary -> other_allowance, ['class' => 'form-control', $salary -> other_allowance? '': 'disabled']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('description_of_other_allowance', __('trans.details'), ['class' => 'control-label']) !!}
                                        {!! Form::text('description_of_other_allowance', $salary -> description_of_other_allowance, ['class' => 'form-control', $salary -> description_of_other_allowance? '': 'disabled']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
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
@push('links')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
@endpush

@push('scripts')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('#birth_date').val() == '' ? $("#birth_date").datepicker().datepicker("setDate", new Date()) : $("#birth_date").datepicker().datepicker();
        $('#date_of_hiring').val() == '' ? $("#date_of_hiring").datepicker().datepicker("setDate", new Date()) : $("#date_of_hiring").datepicker().datepicker();
        $('#date_of_leaving_work').val() != '' ? $("#date_of_leaving_work").datepicker().datepicker(): '';

        // let active_other_allowance_checkbox = $('#active_other_allowance');
        // if($('#other_allowance').val() == '')
        // {
        //     active_other_allowance_checkbox.next('span').text('تفعيل');
        //     active_other_allowance_checkbox.prop('checked', false);
        // }else
        // {
        //     active_other_allowance_checkbox.next('span').text('تعطيل');
        //     active_other_allowance_checkbox.prop('checked', true);
        // }

        $('#active_date_leaving').on("change", function() {
            if($(this).is(':checked')) {
                $(this).next('span').text('تفعيل');
                // $("#date_of_leaving_work, #end_service_allowance").val('');
                $('#date_of_leaving_work, #end_service_allowance').val('').prop('disabled', true);
            }else
            {
                $(this).next('span').text('تعطيل');
                '{{ $employee -> date_of_leaving_work }}' ? $("#date_of_leaving_work").val('{{ $employee -> date_of_leaving_work }}') : $("#date_of_leaving_work").datepicker().datepicker("setDate", new Date());
                '{{ $salary -> end_service_allowance }}' ? $("#end_service_allowance").val('{{ $salary -> end_service_allowance }}') : $("#end_service_allowance").val('');
                $('#date_of_leaving_work, #end_service_allowance').prop('disabled', false);
            }

        });

        $('#active_other_allowance').on("change", function() {
            if($(this).is(':checked')) {
                $(this).next('span').text('تفعيل');
                $('#other_allowance, #description_of_other_allowance').val('').prop('disabled', true);
            }else
            {
                $(this).next('span').text('تعطيل');
                '{{ $salary -> other_allowance }}' ? $("#other_allowance").val('{{ $salary -> other_allowance }}') : $("#other_allowance").val('');
                '{{ $salary -> description_of_other_allowance }}' ? $("#description_of_other_allowance").val('{{ $salary -> description_of_other_allowance }}') : $("#description_of_other_allowance").val('');

                $('#other_allowance, #description_of_other_allowance').prop('disabled', false);

            }

        });
    </script>
@endpush
