<!doctype html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS Files -->
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- RTL Files -->
    <link rel="stylesheet" href="{{ asset('assets/dist/rtl/css/bootstrap.rtl.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/receipt/css/mainReceipt.css') }}">

    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <style>
        table .salary_details {
            margin: 0 auto;
            outline: 1px solid white;
            outline-offset: -2px;
        }
        table .salary_details td {
            padding: 1rem;
            outline: 1px solid black;
        }
        .font-bold {
            font-weight: bold;
        }
        @font-face {
            font-family: myFirstFont;
            src:url({{ asset('assets/dist/fonts/sst-arabic-medium.ttf') }});
        }
    </style>
    <title>{{ $salary_details -> employee -> name }} -  تفاصيل راتب شهر {{ Request::segment(5) }}</title>
</head>
<body>
<div class="buttons_area">
    <button
        id="tax_invoice_print"
        class="btn btn-primary btn-sm btn-block"
        data-link="{{ route('employee.salary_month_details_print', ['employee_salary_log_id' => $salary_details -> id]) }}"
        href="javascript:void(0);"
        onclick="printJS($(this).data('link'))"
    >
        <span> طباعة <i class="fa fa-print"></i></span>
    </button>

{{--    <a href="{{ route('employee.salary_month_details_print', ['employee_salary_log_id' => $salary_details -> id]) }}" class="btn btn-danger btn-sm btn-block">--}}
{{--        <span> تحميل ك PDF <i class="fa fa-paperclip"></i></span>--}}
{{--    </a>--}}

    <a href="{{ route('employee.employees.index') }}" class="btn btn-warning btn-sm btn-block">
        <span>الذهاب الى صفحة الموظفين <i class="fa fa-backward"></i></span>
    </a>

</div>
<div class="container">
    <div class="report">
        <table class="report-container">
            <thead class="report-header">
            <tr>
                <td class="report-header-cell">
                    <div class="header-info" style="margin-bottom: 20px">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-3">
                                    <div class="logo">
                                        <img src="{{ asset('storage/logo.png') }}" alt="logo" width="150" height="100">
                                    </div> <!-- end logo -->
                                </div> <!-- end col 4 -->
                                <div class="col-xs-9">
                                    <div class="company_name_container">
                                        <div class="company_name_inside_elements">
                                            <h4>مركز اس كي بي للاصلاح الميكانيكي</h4>
                                            <h5 style="margin-right: 5px;">SKB AUTO MECHANICAL REPAIR L.L.C</h5>
                                        </div>
                                    </div> <!-- end company name -->
                                </div> <!-- end col 8 -->
                                <div class="col-xs-12">
                                    <div class="col-xs-offset-4 col-xs-4 col-xs-offset-4">
                                        <h5 class="repair_order margin-5">راتب شهر {{ $month .'-'. $year }}</h5>
                                    </div>
                                </div> <!-- end col 12 -->
                            </div> <!-- end col 12 -->

                            <div class="col-xs-12">
                                <div class="report_information">
                                    <div class="padding-5 col-xs-5">
                                        <div class="from_section">
                                            <div class="title">
                                                <div class="padding-0 col-xs-6">
                                                        <span class="arabic">
                                                             بيانات الموظف
                                                        </span> <!-- end span -->

                                                </div>
                                                <div class="padding-0 col-xs-6">
                                                        <span class="english">
                                                            Employee information
                                                        </span> <!-- end span -->
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr> <!-- hr -->
                                            <div class="padding-0 col-xs-6">
                                                <div class="ar_info">
                                                    <div class="col-xs-12 padding-0">
                                                            <span class="arabic">
                                                              اسم الموظف:
                                                            </span> <!-- end span -->
                                                    </div> <!-- end col 12 -->
                                                    <div class="col-xs-12 padding-0">
                                                            <span class="arabic">
                                                                الفرع:
                                                            </span> <!-- end span -->
                                                    </div> <!-- end col 12 -->
                                                </div> <!-- end ar_info -->
                                                <div class="clearfix"></div>
                                            </div> <!-- end col 6 -->
                                            <div class="padding-0 col-xs-6">
                                                <div class="en_info">
                                                    <div class="col-xs-12 padding-0">
                                                            <span class="english">
                                                                {{ $salary_details -> employee -> name }}
                                                            </span> <!-- end span -->
                                                    </div> <!-- end col 12 -->
                                                    <div class="col-xs-12 padding-0">
                                                            <span class="english">
                                                                {{ $salary_details -> employee -> branch ->name }}
                                                            </span> <!-- end span -->
                                                    </div> <!-- end col 12 -->
                                                </div> <!-- end en_info -->
                                                <div class="clearfix"></div>
                                            </div> <!-- end col 6 -->
                                        </div> <!-- end section from -->
                                    </div> <!-- end col 5 -->
                                    <div class="padding-5 col-xs-2">
                                        <div class="work_card_center">
                                            <h5 class="text-center margin-5">Salary Card</h5>
                                            <h5 class="text-center margin-5">بطاقة راتب</h5>
                                        </div> <!-- end work_card_center-->
                                    </div> <!-- end col 2 -->
                                    <div class="padding-5 col-xs-5">
                                        <div class="to_section">
                                            <div class="title">
                                                <div class="col-xs-6 padding-0">
                                                    <span class="arabic">
                                                         بيانات الدخول الى الموقع
                                                    </span> <!-- end span -->
                                                </div> <!-- end col 6 -->
                                                <div class="col-xs-6 padding-0">
                                                    <span class="english">
                                                        Credentials information
                                                    </span> <!-- end span -->
                                                </div> <!-- end col 6 -->
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr> <!-- hr -->
                                            <div class="padding-0 col-xs-6">
                                                <div class="ar_info">
                                                    <div class="padding-0 col-xs-12">
                                                        <span class="arabic">
                                                            البريد الالكترونى:
                                                        </span> <!-- end span -->
                                                    </div>
                                                    <div class="padding-0 col-xs-12">
                                                        <span class="arabic">
                                                            كود الموظف:
                                                        </span> <!-- end span -->
                                                    </div>

                                                </div> <!-- end ar_info -->
                                                <div class="clearfix"></div>
                                            </div> <!-- end col 6 -->
                                            <div class="padding-0 col-xs-6">
                                                <div class="en_info">
                                                    <div class="padding-0 col-xs-12">
                                                        <span class="english">
                                                            xxx@gmail.com
                                                        </span> <!-- end span -->
                                                    </div> <!-- end col 12 -->
                                                    <div class="padding-0 col-xs-12">
                                                        <span class="english">
                                                            25669670
                                                        </span> <!-- end span -->
                                                    </div> <!-- end col 12 -->
                                                </div> <!-- end en_info -->
                                                <div class="clearfix"></div>
                                            </div> <!-- end col 6 -->
                                        </div> <!-- end to section -->
                                    </div> <!-- end col 5 -->
                                </div> <!-- end report_information -->
                                <div class="clearfix"></div>
                                <br>
                                <hr style="border-top: 1px solid #000">
                                <br>
                            </div> <!-- end col 12 -->

                        </div> <!-- end row -->
                    </div>
                </td>
            </tr>
            </thead>
            <tbody class="report-content">
            <tr>
                <td class="report-content-cell">
                    <div class="main">
                        <div class="body">
                            <div class="body_without_terms">
                                <table style="width: 100%;" class=" salary_details text-center">
                                    <tr class="lightgrey">
                                        <td>{{ __('trans.main') }}</td>
                                        <td>{{ __('trans.housing allowance') }}</td>
                                        <td>{{ __('trans.transfer allowance') }}</td>
                                        <td>{{ __('trans.travel allowance') }}</td>
                                        <td>{{ __('trans.other allowance') }}</td>
                                        <td>{{ __('trans.description of other allowance') }}</td>
                                        <td>{{ __('trans.end service allowance') }}</td>
                                        <td class="font-bold">{{ __('trans.total') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $salary_details -> main ?? '_______' }}</td>
                                        <td>{{ $salary_details -> housing_allowance ?? '_______' }}</td>
                                        <td>{{ $salary_details -> transfer_allowance ?? '_______' }}</td>
                                        <td>{{ $salary_details -> travel_allowance ?? '_______' }}</td>
                                        <td>{{ $salary_details -> other_allowance ?? '_______' }}</td>
                                        <td>{{ $salary_details -> description_of_other_allowance ?? '_______' }}</td>
                                        <td>{{ $salary_details -> end_service_allowance ?? '_______' }}</td>
                                        <td class="font-bold">{{ $salary_details -> total_salary ?? '_______' }}</td>
                                    </tr>

                                    <tr class="lightgrey">
                                        <td>{{ __('trans.total advances') }}</td>
                                        <td>{{ __('trans.total rewards') }}</td>
                                        <td>{{ __('trans.total vacations') }}</td>
                                        <td>{{ __('trans.total discounts') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $salary_details -> total_advances ?? '_______' }}</td>
                                        <td>{{ $salary_details -> total_rewards ?? '_______' }}</td>
                                        <td>{{ $salary_details -> total_vacations ?? '_______' }}</td>
                                        <td>{{ $salary_details -> total_discounts ?? '_______' }}</td>
                                    </tr>
                                    <tr class="lightgrey">
                                        <td class="font-bold">{{ __('trans.final salary') }}</td>
                                        <td class="font-bold">{{ $salary_details -> final_salary ?? '_______' }}</td>
                                    </tr>
                                </table>
                                <div class="clearfix"></div>
                            </div>
                            <div class="body_terms">
                                <div class="terms_of_repair">
                                    <div class="clearfix"></div>
                                    <h5 class="lightgrey terms_of_repair_title">تاريخ القبض : {{ \Carbon\Carbon::parse($salary_details->updated_at)->format('d-m-Y') }}</h5>
                                    <span class="terms_of_repair_content">
                                       <div dir="rtl" lang="ar-sa">
                                           {{--@include('admin.includes.terms_of_repair')--}}
                                       </div>

{{--                                        <hr style="border-top: 1px solid #000; width: 300px; margin: 0 auto;">--}}

                                        <span class="client_signature">
                                                <span class="row">
                                                    <div class="text-center col-xs-6">
                                                        {{--@if ($client_signature_entry)--}}
                                                            <p style="margin-bottom: 15px">توقيع المحاسب</p>
                                                            <p>{{ auth()->user()->name }}</p>
{{--                                                            <img src="" alt="" height="100" width="200">--}}
                                                        {{--@endif--}}

                                                    </div>
                                                    <div class="text-center col-xs-6">
                                                            <p style="margin-bottom: 15px">توقيع الموظف</p>
                                                            @if ($employee_salary_signature)
                                                                <img src="{{ asset('storage' . DIRECTORY_SEPARATOR . 'employees_signature' . DIRECTORY_SEPARATOR . 'salary_signature' . DIRECTORY_SEPARATOR . $salary_details -> id  . DIRECTORY_SEPARATOR . $employee_salary_signature -> image_name) }}" alt="" height="100" width="200">
                                                            @endif
                                                    </div>

                                                </span>
                                        </span>
                                    </span>
                                </div> <!-- end terms_of_repair -->
                            </div> <!-- end col 12 -->
                        </div> <!-- end body -->
                    </div> <!-- end main -->
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</div>

<!-- Java Script Files -->
<!-- jQuery 3 -->
<script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/receipt/js/mainReceipt.js') }}"></script>

<script>

</script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
</body>
</html>
