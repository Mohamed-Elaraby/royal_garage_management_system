@php
    $pageType = __('trans.create');
    $pageItem = __('trans.advance')

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
                    {!! Form::open(['route' => 'employee.advances.store', 'method' => 'post']) !!}
                    {!! Form::hidden('employee_id', $employee -> id) !!}
                    <div class="form-group">
                        {!! Form::label('amount', __('trans.amount'), ['class' => 'control-label']) !!}
                        {!! Form::text('amount', null, ['class' => 'form-control']) !!}
                    </div>
                    <div id="type_group">
                        <div class="form-group">
                            {!! Form::label('type', __('trans.type'), ['class' => 'control-label']) !!}
                            {!! Form::select('type', ['' => '__ اختر نوع السلفة __','deducted_directly' => __('trans.deducted directly'), 'schedule' => __('trans.schedule')] , null , ['class' => 'form-control', 'id' => 'type']) !!}
                        </div>
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

        $('#type').on("change", function() {
            let type = $(this).val();
            if (type == 'schedule')
            {
                let content =
                    '<div id="number_of_schedule_group" class="form-group">\n' +
                    '<label for="number_of_schedule" class="form-label">عدد مرات الجدولة</label>\n' +
                    '<input type="number" min="1" name="number_of_schedule" class="form-control number_of_schedule" id="number_of_schedule">\n' +
                    '</div>';
                $('#type_group').append(content);
            }
            else
            {
                $('#number_of_schedule_group').remove();
            }
        });
    </script>
@endpush
