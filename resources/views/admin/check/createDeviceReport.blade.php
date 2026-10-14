@php
$pageType = __('trans.upload');
$pageItem = __('trans.device report')

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
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="card-header">
                    <h3 class="text-center"><i class="fa fa-user-secret"></i> {{ $pageType .' '. $pageItem }}</h3>
                </div>
                <div class="card-body">

                    <div class="text-center" style="font-size: 3vw;">
                        <span class="image_button active">{{ __('trans.image') }}</span> |
                        <span class="file_button">{{ __('trans.file') }}</span>
                    </div>
                    {!! Form::open(['route' => 'admin.check.storeDeviceReport', 'method' => 'post', 'files' => true]) !!}
                        {!! Form::hidden('check_id', $check_id) !!}
                        {!! Form::hidden('check_number', $check_number) !!}

                        <div class="form-group" id="image_input">
                            {!! Form::label('report_device', __('trans.image'), ['class' => 'control-label active']) !!}
                            {!! Form::file('report_device[]', ['class' => 'form-control','multiple']) !!}
                        </div>

                        <div class="form-group" id="file_input" style="display: none">
                            {!! Form::label('report_device_file', __('trans.file'), ['class' => 'control-label active']) !!}
                            {!! Form::file('report_device_file', ['class' => 'form-control','multiple']) !!}
                        </div>
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
    <style>
        .image_button:hover, .file_button:hover
        {
            cursor: pointer;
        }
        .active
        {
            color: #FFFFFF;
            background: #00a65a;
            padding: 10px;
            border-radius: 10px;

        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.image_button').on('click', function () {
                $(this).addClass('active').siblings().removeClass('active');
                $('#image_input').show().siblings().hide();
            })
            $('.file_button').on('click', function () {
                $(this).addClass('active').siblings().removeClass('active');
                $('#file_input').show().siblings().hide();
            })
        })
    </script>
@endpush
