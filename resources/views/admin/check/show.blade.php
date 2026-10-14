@extends('admin.layouts.app')

@section('title', __('trans.details data for examination'))

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
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h3 style="margin: 0; padding: 0; display: inline"> <i class="fa fa-check"></i> {{ __('trans.details data for examination') }}</h3>
                                </div>
                                <div class="col-xs-6">
                                    @if (Auth::user()->hasPermission('update-check'))
                                        <a href="{{ route('admin.check.edit', $check->id) }}" class="btn btn-sm btn-primary " title="تعديل بيانات الفحص"> <i class="fa fa-edit"></i> {{ __('trans.edit check') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6">
                            <a href="{{ url()->previous() }}" class="btn btn-success btn-sm pull-right"><i class="fa fa-backward"></i> {{ __('trans.go back') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h2 class="text-center">[ {{ __('trans.check number') .' - '. $check -> check_number}} ]</h2>
                    <table id="check_table" class="table table-bordered table-striped">
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.editor') }} :</th>
                            <td>{{ $check -> user -> name }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.branch') }} :</th>
                            <td>{{ $check -> branch -> name }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.client name') }} :</th>
                            <td>{{ $check -> car? $check -> car -> client -> name : ''}}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.phone') }} :</th>
                            <td>{{ $check -> car? $check -> car -> client -> phone: '' }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.counter number') }} :</th>
                            <td>{{ $check -> counter_number }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.chassis number') }} :</th>
                            <td>{{ $check -> chassis_number }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.plate number') }} :</th>
                            <td>{{ $check -> plate_number }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.car color') }} :</th>
                            <td>{{ $check -> car_color }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.car type') }} :</th>
                            <td>{{ $check -> car_type }}</td>
                        </tr>

                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.car size') }} :</th>
                            <td>{{ $check -> car_size }}</td>
                        </tr>

                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.model') }} :</th>
                            <td>{{ $check -> car_model }}</td>
                        </tr>

                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.engine number') }} :</th>
                            <td>{{ $check -> car_engine }}</td>
                        </tr>

                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.car development code') }} :</th>
                            <td>{{ $check -> car_development_code }}</td>
                        </tr>

                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.driver name') }} :</th>
                            <td>{{ $check -> driver_name }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.fuel level') }} :</th>
                            <td>{{ $check -> fuel_level }}%</td>
                        </tr>

                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.technical name') }} :</th>
                            <td>
                                @foreach ($check -> technicals as $key => $technical)
                                    {{ $key >  $technical -> count ? ' , ' :''}}{{ $technical -> name}}
                                @endforeach
                            </td>
                        </tr>

                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.engineer name') }} :</th>
                            <td>{{ $check -> engineer?$check -> engineer -> name:'' }}</td>
                        </tr>

                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.status') }} :</th>
                            <td>{{ $check -> checkStatus? $check -> checkStatus -> name: '' }}</td>
                        </tr>

                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.repairs needed') }} :</th>
                            <td>{!! $check -> car_status_report !!}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.notes when entering the car') }} :</th>
                            <td>{!! $check -> car_status_report_note !!}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.car images note') }} :</th>
                            <td>{!! $check -> car_images_note !!}</td>
                        </tr>

                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.car entry date') }} :</th>
                            <td style="direction:ltr; {!! LaravelLocalization::getCurrentLocale() == 'ar'?"float:right":"float:left" !!}">
                                {{ $check -> created_at -> format('d/m/Y - h:i:s a') }}
                            </td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.car exit date') }} :</th>
                            <td style="direction:ltr; {!! LaravelLocalization::getCurrentLocale() == 'ar'?"float:right":"float:left" !!}">
                                {{ $check -> car_exit_date?$check -> car_exit_date -> format('d/m/Y - h:i:s a'):'' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('links')

@endpush

@push('scripts')

@endpush

