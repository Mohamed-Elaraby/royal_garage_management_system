@php
    $previous_month = \Carbon\Carbon::now()->subMonth()->format('m');
    $current_month = \Carbon\Carbon::now()->month;
    $previous_year = \Carbon\Carbon::now()->subYear()->format('Y');
    $current_year = \Carbon\Carbon::now()->year;
@endphp
@extends('admin.layouts.app')

@section('title', __('trans.salary log'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="error_messages text-center">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('delete'))
                        <div class="alert alert-danger">
                            {{ session('delete') }}
                        </div>
                    @endif
                </div>
                <div class="card-header" style="margin: 20px 0">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h3 style="margin: 0; padding: 0; display: inline"> <i class="fa fa-money"></i> {{ __('trans.salary log') }}</h3>
                                </div>
                                <div class="col-xs-6">
{{--                                    @if (Auth::user()->hasPermission('update-employees'))--}}
                                        <a href="{{ route('employee.salaries.receipt.view', $employee->id) }}" class="btn btn-sm btn-success pull-right" title="قبض الراتب"> <i class="fa fa-money"></i> تفاصيل راتب شهر {{ $previous_month }} - {{ $current_month == 1 ? $previous_year : $current_year }} </a>
{{--                                    @endif--}}

{{--                                    @if (Auth::user()->hasPermission('read-salaries'))--}}
{{--                                    @endif--}}
                                </div>
                            </div>
                        </div>

{{--                        <div class="col-xs-6">--}}
{{--                            <a href="{{ url()->previous() }}" class="btn btn-success btn-sm pull-right"><i class="fa fa-backward"></i> {{ __('trans.go back') }}</a>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="card-body">
                    <h2 class="text-center">[ {{ __('trans.employee name') .' - '. $employee -> name}} ]</h2>
                    <div class="row">
{{--                        {{ dd($years) }}--}}
                        @foreach ($years as $year => $details)
                        <div class="col-md-4">
                            <div class="box box-default collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">{{ $year }}</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->

                                <div class="box-body">
                                    <ul>
                                        @foreach($details as $data)
                                            @php($target_date = \Carbon\Carbon::parse($data['salary_month']))
                                            <li>

                                                <a href="{{ route('employee.salaries.salary_month_details', [$employee -> id, $target_date -> month, $target_date -> year]) }}">
                                                    تفاصيل راتب شهر {{ $target_date -> month }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')

@endpush

@push('scripts')

@endpush

