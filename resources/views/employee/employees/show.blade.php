@extends('admin.layouts.app')

@section('title', __('trans.details data for employee'))

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
                                    <h3 style="margin: 0; padding: 0; display: inline"> <i class="fa fa-employee"></i> {{ __('trans.details data for employee') }}</h3>
                                </div>
                                <div class="col-xs-6">
                                    @if (Auth::user()->hasPermission('update-employees'))
                                        <a href="{{ route('employee.employees.edit', $employee->id) }}" class="btn btn-sm btn-primary " title="تعديل بيانات الفحص"> <i class="fa fa-edit"></i> {{ __('trans.edit employee') }}</a>
                                    @endif

{{--                                    @if (Auth::user()->hasPermission('read-salaries'))--}}
{{--                                    @endif--}}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6">
                            <a href="{{ url()->previous() }}" class="btn btn-success btn-sm pull-right"><i class="fa fa-backward"></i> {{ __('trans.go back') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h2 class="text-center">[ {{ __('trans.employee name') .' - '. $employee -> name}} ]</h2>
                    <table id="employee_table" class="table table-bordered table-striped">
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.employee name') }} :</th>
                            <td>{{ $employee -> name }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.birth date') }} :</th>
                            <td>{{ $employee -> birth_date }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.date of hiring') }} :</th>
                            <td>{{ $employee -> date_of_hiring }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.job title') }} :</th>
                            <td>{{ $employee -> jobTitle-> name }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.id number') }} :</th>
                            <td>{{ $employee -> id_number }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.passport number') }} :</th>
                            <td>{{ $employee -> passport_number }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.nationality') }} :</th>
                            <td>{{ $employee -> nationality -> nationality }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.date of leaving work') }} :</th>
                            <td>{{ $employee -> date_of_leaving_work }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.branch') }} :</th>
                            <td>{{ $employee -> branch -> name }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.username') }} :</th>
                            <td>{{ $employee -> username }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.password') }} :</th>
                            <td>{{ $employee -> password }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="bg-gray">{{ __('trans.status') }} :</th>
                            <td>{{ $employee -> status }}</td>
                        </tr>


{{--                        <tr>--}}
{{--                            <th width="30%" class="bg-gray">{{ __('trans.car exit date') }} :</th>--}}
{{--                            <td style="direction:ltr; {!! LaravelLocalization::getCurrentLocale() == 'ar'?"float:right":"float:left" !!}">--}}
{{--                                {{ $employee -> car_exit_date?$employee -> car_exit_date -> format('d/m/Y - h:i:s a'):'' }}--}}
{{--                            </td>--}}
{{--                        </tr>--}}
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

