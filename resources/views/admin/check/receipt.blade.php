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
        @font-face {
            font-family: myFirstFont;
            src:url({{ asset('assets/dist/fonts/sst-arabic-medium.ttf') }});
        }
    </style>
    <title>ايصال استلام السيارة</title>
</head>
<body>
<div class="buttons_area">
    <button
        id="tax_invoice_print"
        class="btn btn-primary btn-sm btn-block"
        data-link="{{ route('admin.check.receipt_print', ['check_id' => $check -> id]) }}"
        href="javascript:void(0);"
        onclick="printJS($(this).data('link'))"
    >
        <span> طباعة <i class="fa fa-print"></i></span>
    </button>

    <a href="{{ route('admin.check.receipt_download', ['check_id' => $check -> id]) }}" class="btn btn-danger btn-sm btn-block">
        <span> تحميل ك PDF <i class="fa fa-paperclip"></i></span>
    </a>

    <a href="{{ route('admin.check.index') }}" class="btn btn-warning btn-sm btn-block">
        <span>الذهاب الى صفحة الفحوصات <i class="fa fa-backward"></i></span>
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
                                <div class="col-xs-4">
                                    <div class="logo">
                                        <img src="{{ asset('storage/logo.png') }}" alt="logo" width="100" height="100">
                                    </div> <!-- end logo -->
                                </div> <!-- end col 4 -->
                                <div class="col-xs-8">
                                    <div class="company_name_container">
                                        <div class="company_name_inside_elements">
                                            <h4>{{ __('trans.Royal Garage group') }}</h4>
                                            <h5 style="margin-right: 5px;">Royal Garage Group</h5>
                                        </div>
                                    </div> <!-- end company name -->
                                </div> <!-- end col 8 -->
                                <div class="col-xs-12">
                                    <div class="col-xs-offset-4 col-xs-4 col-xs-offset-4">
                                        <h5 class="repair_order margin-5">Repair Order: {{ $check -> check_number }}</h5>
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
                                                             بيانات الفرع
                                                        </span> <!-- end span -->

                                                    </div>
                                                    <div class="padding-0 col-xs-6">
                                                        <span class="english">
                                                            Branch information
                                                        </span> <!-- end span -->
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <hr> <!-- hr -->
                                                <div class="padding-0 col-xs-4">
                                                    <div class="ar_info">
                                                        <div class="col-xs-12 padding-0">
                                                            <span class="arabic">
                                                              اسم الفرع:
                                                            </span> <!-- end span -->
                                                        </div> <!-- end col 12 -->
                                                        <div class="col-xs-12 padding-0">
                                                            <span class="arabic">
                                                                رقم الهاتف:
                                                            </span> <!-- end span -->
                                                        </div> <!-- end col 12 -->
                                                    </div> <!-- end ar_info -->
                                                    <div class="clearfix"></div>
                                                </div> <!-- end col 4 -->
                                                <div class="padding-0 col-xs-4">
                                                    <div class="branch_data">
                                                        <div class="col-xs-12 padding-0">
                                                            <span class="arabic text-center">
                                                                {{ $check -> branch? $check -> branch -> name: '' }}
                                                            </span> <!-- end span -->
                                                        </div> <!-- end col 12 -->
                                                        <div class="col-xs-12 padding-0">
                                                            <span class="arabic text-center">
                                                                {{ $check -> branch? $check -> branch -> phone: '' }}
                                                            </span> <!-- end span -->
                                                        </div> <!-- end col 12 -->
                                                    </div> <!-- end ar_info -->
                                                    <div class="clearfix"></div>
                                                </div> <!-- end col 4 -->
                                                <div class="padding-0 col-xs-4">
                                                    <div class="en_info">
                                                        <div class="col-xs-12 padding-0">
                                                            <span class="english">
                                                                Branch name
                                                            </span> <!-- end span -->
                                                        </div> <!-- end col 12 -->
                                                        <div class="col-xs-12 padding-0">
                                                            <span class="english">
                                                                Mobile number
                                                            </span> <!-- end span -->
                                                        </div> <!-- end col 12 -->
                                                    </div> <!-- end en_info -->
                                                    <div class="clearfix"></div>
                                                </div> <!-- end col 4 -->
                                            </div> <!-- end section from -->
                                    </div> <!-- end col 5 -->
                                    <div class="padding-5 col-xs-2">
                                        <div class="work_card_center">
                                            <h6 class="text-center margin-5">Job Card</h6>
                                            <h6 class="text-center margin-5">بطاقة عمل</h6>
                                        </div> <!-- end work_card_center-->
                                    </div> <!-- end col 2 -->
                                    <div class="padding-5 col-xs-5">
                                        <div class="to_section">
                                            <div class="title">
                                                <div class="col-xs-6 padding-0">
                                                    <span class="arabic">
                                                         بيانات العميل
                                                    </span> <!-- end span -->
                                                </div> <!-- end col 6 -->
                                                <div class="col-xs-6 padding-0">
                                                    <span class="english">
                                                        Client information
                                                    </span> <!-- end span -->
                                                </div> <!-- end col 6 -->
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr> <!-- hr -->
                                            <div class="padding-0 col-xs-4">
                                                <div class="ar_info">
                                                    <div class="padding-0 col-xs-12">
                                                        <span class="arabic">
                                                            اسم العميل:
                                                        </span> <!-- end span -->
                                                    </div>
                                                    <div class="padding-0 col-xs-12">
                                                        <span class="arabic">
                                                            رقم الهاتف:
                                                        </span> <!-- end span -->
                                                    </div>

                                                </div> <!-- end ar_info -->
                                                <div class="clearfix"></div>
                                            </div> <!-- end col 4 -->
                                            <div class="padding-0 col-xs-4">
                                                <div class="client_data">
                                                    <div class="padding-0 col-xs-12">
                                                        <span class="arabic text-center">
                                                            {{ $check -> car? $check -> car -> client -> name: '' }}
                                                        </span> <!-- end span -->
                                                    </div> <!-- end col 12 -->
                                                    <div class="padding-0 col-xs-12">
                                                        <span class="arabic text-center">
                                                            {{ $check -> car -> client? $check -> car -> client -> phone: '' }}
                                                        </span> <!-- end span -->
                                                    </div> <!-- end col 12 -->

                                                </div> <!-- end ar_info -->
                                                <div class="clearfix"></div>
                                            </div> <!-- end col 4 -->
                                            <div class="padding-0 col-xs-4">
                                                <div class="en_info">
                                                    <div class="padding-0 col-xs-12">
                                                        <span class="english">
                                                            Client name:
                                                        </span> <!-- end span -->
                                                    </div> <!-- end col 12 -->
                                                    <div class="padding-0 col-xs-12">
                                                        <span class="english">
                                                            Mobile number:
                                                        </span> <!-- end span -->
                                                    </div> <!-- end col 12 -->
                                                </div> <!-- end en_info -->
                                                <div class="clearfix"></div>
                                            </div> <!-- end col 4 -->
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
                                <div class="row_1">
                                    <div class="data_head">
                                        <div class="padding-0 col-xs-12 lightgrey">
                                            <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    رقم العداد
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    |Counter No.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                            <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    رقم الشاسية
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    |Chassis No.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                            <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    رقم اللوحة
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    |Plate No.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                            <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    لون السيارة
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    Car color.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                        </div> <!-- end col 12 -->
                                    </div> <!-- end data head -->
                                    <div class="data_body">
                                        <div class="padding-0 col-xs-12">
                                            <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> counter_number }}
                                        </span>
                                            <span class="padding-0 text-center col-xs-3">
                                           {{ $check -> chassis_number }}
                                        </span>
                                            <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> plate_number }}
                                        </span>
                                            <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> car_color }}
                                        </span>
                                        </div> <!-- end col 12 -->
                                    </div> <!-- end data body -->
                                </div> <!-- end row 1 -->
                                <div class="row_2">
                                    <div class="data_head">
                                        <span class="padding-0 col-xs-12 lightgrey">
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    النوع
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    |Type.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    حجم السيارة
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    |Car size.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    الموديل
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    Model.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    رقم المحرك
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    Engine Number.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                    </span> <!-- end col 12 -->
                                    </div> <!-- end data head -->
                                    <div class="data_body">
                                        <div class="padding-0 col-xs-12">
                                            <span class="padding-0 text-center col-xs-3">
                                                {{ $check -> car_type }}
                                            </span>
                                            <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> car_size}}
                                        </span>
                                            <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> car_model }}
                                        </span>
                                            <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> car_engine }}
                                        </span>
                                        </div> <!-- end col 12 -->
                                    </div> <!-- end data body -->
                                </div> <!-- end row 2 -->
                                <div class="row_3">
                                    <div class="data_head">
                                        <span class="padding-0 col-xs-12 lightgrey">
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    كود التطوير
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    |Dev Code.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    مستوى البنزين
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    Fuel level.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    اسم المحرر
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    |Created by.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    اسم السائق
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    Driver name.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                    </span> <!-- end col 12 -->
                                    </div> <!-- end data head -->
                                    <div class="data_body">
                                    <span class="padding-0 col-xs-12">
                                        <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> car_development_code }}
                                        </span>
                                        <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> fuel_level }}%
                                        </span>
                                        <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> user? $check -> user -> name: ''  }}
                                        </span>
                                        <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> driver_name  }}
                                        </span>
                                    </span> <!-- end col 12 -->
                                    </div> <!-- end data body -->
                                </div> <!-- end row 3 -->
                                <div class="row_4">
                                    <div class="data_head">
                                    <span class="padding-0 col-xs-12 lightgrey">
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    تاريخ الدخول
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    |Date in.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    وقت الدخول
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    |Time in.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    تاريخ الخروج
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    |Date out.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->
                                        <span class="padding-0 col-xs-3">
                                            <span class="padding-0 col-xs-6">
                                                <span class="arabic">
                                                    وقت الخروج
                                                </span>
                                            </span>
                                            <span class="padding-0 col-xs-6">
                                                <span class="english">
                                                    Time out.
                                                </span>
                                            </span>
                                        </span> <!-- end col 3 -->

                                    </span> <!-- end col 12 -->
                                    </div> <!-- end data head -->
                                    <div class="data_body">
                                    <span class="padding-0 col-xs-12">
                                        <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> created_at -> format('d/m/Y') }}
                                        </span>
                                        <span class="padding-0 text-center col-xs-3" style="direction: ltr!important;">
                                            {{ $check -> created_at -> format('h:i:s a') }}
                                        </span>
                                        <span class="padding-0 text-center col-xs-3">
                                            {{ $check -> car_exit_date ? $check -> car_exit_date -> format('d/m/Y') :''}}
                                        </span>
                                        <span class="padding-0 text-center col-xs-3" style="direction: ltr!important;">
                                            {{ $check -> car_exit_date ? $check -> car_exit_date -> format('h:i:s a'):'' }}
                                        </span>
                                    </span> <!-- end col 12 -->
                                    </div> <!-- end data body -->
                                </div> <!-- end row 4 -->
                                <div class="clearfix"></div>
                                <div class=" padding-0">
                                    <div class="repair_needed">
                                        <h5 class="lightgrey repair_needed_title">الإصلاحات المطلوبة</h5>
                                        <span class="repair_needed_content">
                                            {!! $check -> car_status_report !!}
                                        </span>
                                    </div> <!-- end repair needed -->

                                </div> <!-- end col 12 -->
                                @if ($check -> car_status_report_note)
                                    <div class="col-xs-12 padding-0">
                                        <div class="car_status_report_note">
                                            <div class="clearfix"></div>
                                            <h5 class="lightgrey car_status_report_note_title"> ملاحظات علي الإصلاحات المطلوبة</h5>
                                            <span class="car_status_report_note_content">
                                            {!! $check -> car_status_report_note !!}
                                        </span>
                                        </div>
                                    </div> <!-- end col 12 -->
                                @endif
                                @if ($check -> car_images_note)
                                    <div class="col-xs-12 padding-0">
                                        <div class="car_images_note">
                                            <div class="clearfix"></div>
                                            <h5 class="lightgrey car_images_note_title">ملاحظات علي صور السيارة</h5>
                                            <span class="car_images_note_content">
                                            {!! $check -> car_images_note !!}
                                        </span>
                                        </div>
                                    </div> <!-- end col 12 -->
                                @endif
                                <div class="clearfix"></div>
                            </div>
                            <div class="body_terms">
                                <div class="terms_of_repair">
                                    <div class="clearfix"></div>
                                    <h5 class="lightgrey terms_of_repair_title">شروط التصليح</h5>
                                    <span class="terms_of_repair_content">
                                       <div dir="rtl" lang="ar-sa">
                                           @include('admin.includes.terms_of_repair')
                                       </div>

                                        <hr style="border-top: 1px solid #000; width: 300px; margin: 0 auto;">
                                        أقر ان جميع الأعمال المبينة أعلاه وقد أنجزت وقد استلمت السيارة بحالة جيدة
                                        <br>
                                        <span class="client_signature">
                                                <span class="row">
                                                    <div class="text-center col-xs-6">
                                                        @if ($client_signature_entry)
                                                            <p style="margin-bottom: 15px">توقيع العميل عند دخول السيارة</p>
                                                            <img src="{{ asset('storage' . DIRECTORY_SEPARATOR . 'check_cars' . DIRECTORY_SEPARATOR . 'signature_entry' . DIRECTORY_SEPARATOR . $check->check_number  . DIRECTORY_SEPARATOR . $client_signature_entry -> image_name) }}" alt="" height="100" width="200">
                                                        @endif
                                                    </div>
                                                    <div class="text-center col-xs-6">
                                                        @if ($client_signature_exit)
                                                            <p style="margin-bottom: 15px">توقيع العميل عند خروج السيارة</p>
                                                            <img src="{{ asset('storage' . DIRECTORY_SEPARATOR . 'check_cars' . DIRECTORY_SEPARATOR . 'signature_exit' . DIRECTORY_SEPARATOR . $check->check_number  . DIRECTORY_SEPARATOR . $client_signature_exit -> image_name) }}" alt="" height="100" width="200">
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
            <tfoot class="report-footer">
            <tr>
                <td class="report-footer-cell">
                    <br>
                    <hr style="border-top: 1px solid #000">
                    <br>
                    <div class="footer-info" style=" margin-top: 10px; ">
                        <table class="table table-borderless" style="font-size: x-small;">
                            <thead>
                                <th></th>
                                <th>فرع 1</th>
                                <th>فرع 2</th>
                                <th>فرع 3</th>
                                <th>فرع 4</th>
                                <th>فرع 5</th>
                                <th>فرع 6</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th></th>
                                    <td>صيانة BMW</td>
                                    <td>صيانة BMW</td>
                                    <td>صيانة سيارات أوروبية</td>
                                    <td>سمكرة وبوية</td>
                                    <td>قطع غيار مستعملة</td>
                                    <td>صيانة سيارات أوروبية</td>
                                </tr>
                                <tr>
                                    <th>الموقع</th>
                                    <td>مخرج 18 صناعية الدائرى</td>
                                    <td>حي الياسمين مخرج 5</td>
                                    <td>مخرج 17 الصناعية القديمة</td>
                                    <td>مخرج 18 بجوار الفرع الرئيسي</td>
                                    <td>مخرج 17 حي السلي</td>
                                    <td>مخرج 7 حي النرجس</td>
                                </tr>
                                <tr>
                                    <th>الهاتف</th>
                                    <td>0554773357</td>
                                    <td>0557267766</td>
                                    <td>0500681848</td>
                                    <td>0508196341</td>
                                    <td>0557367766</td>
                                    <td>0554884558</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>

    </div>
</div>

    <!-- Java Script Files -->
    <!-- jQuery 3 -->
    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    @if (LaravelLocalization::getCurrentLocale() === 'ar')
        <!-- RTL Js Files -->
        <script src="{{ asset('assets/dist/rtl/js/adminlte-rtl.js') }}"></script>
    @endif
    <script src="{{ asset('assets/receipt/js/mainReceipt.js') }}"></script>

<script>

</script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
</body>
</html>
