<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $check -> check_number }}.pdf</title>
</head>
<style>
    .footer-info td{
        padding-left: 10px;
    }
    .lightgrey
    {
        background-color: lightgrey;
    }
    .report-container {
        page-break-after:always;
    }
    .repair_order
    {
        padding: 5px 2px;
        border: 1px solid #000;
        text-align: center;
        width: 200px;
        margin: 0 auto;
    }
    body {

        direction: rtl;
        font-size: 10px;
    }
    @page {
        header: page-header;
        footer: page-footer;
    }
</style>
<body>
<htmlpageheader name="page-header">
    <div style="font-size: 20px">
        <span class="org_logo">
            <img style="float: right"  src="storage/logo.png" width="100" height="100"/>
        </span>
        <div class="org_name" style="
        width: 100%;
        text-align: center;
        padding-top: 10px;
        padding-left: 110px;
">
            <span>{{ __('trans.Royal Garage group') }}</span><br>
            <span> Royal Garage group </span>
        </div>
        <table width="100%" class="items">
            <tr>
                <td width="10%">
                    بيانات الفرع
                </td>
                <td width="10%" align="left">
                    Branch Info
                </td>
                <td colspan="3" align="center" style="margin:10px; border: #000000 1px solid; font-size: 15px; font-weight: bold">
                    Repair Order: {{ $check -> check_number }}
                </td>
                <td width="10%">
                    بيانات العميل
                </td>
                <td width="10%" align="left">
                    Client Info
                </td>
            </tr>
            <tr>
                <td width="10%">
                    اسم الفرع:
                </td>
                <td width="10%" align="center">
                    {{ $check -> branch -> name }}
                </td>
                <td width="10%" align="left">
                    :Branch Name
                </td>
                <td width="10%" align="center" style="margin:10px; border: #000000 1px solid; font-size: 15px; font-weight: bold">
                    Job Card
                </td>
                <td width="10%">
                    اسم العميل:
                </td>
                <td width="10%" align="center">
                    {{ $check -> car -> client -> name }}
                </td>
                <td width="10%" align="left">
                    :Client Name
                </td>
            </tr>
            <tr>
                <td width="10%">
                    رقم الهاتف:
                </td>
                <td width="10%" align="center">
                    {{ $check -> branch -> phone }}
                </td>
                <td width="10%" align="left">
                    :Mobile Number
                </td>

                <td width="10%" align="center" style="margin:10px; border: #000000 1px solid; font-size: 15px; font-weight: bold">
                    بطاقة عمل
                </td>

                <td width="10%">
                    رقم الهاتف:
                </td>
                <td width="10%" align="center">
                    {{ $check -> car -> client -> phone }}
                </td>
                <td width="10%" align="left">
                    :Mobile Number
                </td>
            </tr>
        </table>
        <br>
        <hr style="border-top: 1px solid #000">
        <br>
    </div>
</htmlpageheader>


<div class="body">
    <div class="body_without_terms">
        <table width="100%">
            <tr class="lightgrey">
                <td align="center" width="25%">
                    <span>
                        رقم العداد
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        رقم الشاسية
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        رقم اللوحة
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        لون السيارة
                    </span>
                </td>
            </tr>
            <tr>
                <td align="center" width="25%">
                    <span>
                        {{ $check -> counter_number }}
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        {{ $check -> chassis_number }}
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        {{ $check -> plate_number }}
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                         {{ $check -> car_color }}
                    </span>
                </td>
            </tr><!-- end row 1 -->
            <tr class="lightgrey">
                <td align="center" width="25%">
                    <span>
                        النوع
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        حجم السيارة
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        الموديل
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        رقم المحرك
                    </span>
                </td>
            </tr>
            <tr>
                <td align="center" width="25%">
                    <span>
                        {{ $check -> car_type }}
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        {{ $check -> car_size}}
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        {{ $check -> car_model }}
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                         {{ $check -> car_engine }}
                    </span>
                </td>
            </tr><!-- end row 2 -->
            <tr class="lightgrey">
                <td align="center" width="25%">
                    <span>
                        كود التطوير
                    </span>
                </td>
                <td align="center" width="25%">
                     <span>
                        مستوى البنزين
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        اسم المحرر
                    </span>
                </td>
                <td align="center" width="25%">
                     <span>
                        اسم السائق
                    </span>
                </td>
            </tr>
            <tr>
                <td align="center" width="25%">
                    <span>
                       {{ $check -> car_development_code }}
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        {{ $check -> fuel_level }}%
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        {{ $check -> user? $check -> user -> name: ''  }}
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                         {{ $check -> driver_name  }}
                    </span>
                </td>
            </tr><!-- end row 3 -->
            <tr class="lightgrey">
                <td align="center" width="25%">
                    <span>
                        تاريخ الدخول
                    </span>
                </td>
                <td align="center" width="25%">
                     <span>
                        وقت الدخول
                    </span>
                </td>
                <td align="center" width="25%">
                    <span>
                        تاريخ الخروج
                    </span>
                </td>
                <td align="center" width="25%">
                     <span>
                        وقت الخروج
                    </span>
                </td>
            </tr>
            <tr>
                <td align="center" width="25%">
                    <span>
                        {{ $check -> created_at -> format('d/m/Y') }}
                    </span>
                </td>
                <td align="center" width="25%">
                    <span style="direction: rtl!important;">
                        {{ $check -> created_at -> format('h:i:s a') }}
                    </span>
                </td>
                <td align="center" width="25%">
                   <span>
                        {{ $check -> car_exit_date ? $check -> car_exit_date -> format('d/m/Y') :''}}
                    </span>
                </td>
                <td align="center" width="25%">
                     <span style="direction: ltr!important;">
                        {{ $check -> car_exit_date ? $check -> car_exit_date -> format('h:i:s a'):'' }}
                    </span>
                </td>
            </tr><!-- end row 4 -->
        </table>
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
    <div class="report-container"></div>
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
                                        <br>
                                        <span class="client_signature">
                                            <table width="100%">
                                                <tr>
                                                    <td width="50%" align="center">
                                                        @if ($client_signature_entry)
                                                            <p style="margin-bottom: 15px">توقيع العميل عند دخول السيارة</p>
                                                            <br>
                                                            <img src="storage/check_cars/signature_entry/{{ $check->check_number }}/{{$client_signature_entry -> image_name}}" alt="" height="100" width="200">
                                                        @endif
                                                    </td>
                                                    <td width="50%" align="center">
                                                        @if ($client_signature_exit)
                                                            <p style="margin-bottom: 15px">توقيع العميل عند خروج السيارة</p>
                                                            <br>
                                                            <img src="storage/check_cars/signature_exit/{{ $check->check_number }}/{{$client_signature_exit -> image_name}}" alt="" height="100" width="200">
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>

                                        </span>
                                    </span>
        </div> <!-- end terms_of_repair -->
    </div> <!-- end col 12 -->
</div>


<htmlpagefooter name="page-footer">
    <hr style="border-top: 1px solid #000">
    <div class="footer-info" style=" margin-top: 10px; ">
        <table class="table table-borderless" align="center">
            <tbody>
            <tr>
                <th></th>
                <th align="right">فرع 1</th>
                <th align="right">فرع 2</th>
                <th align="right">فرع 3</th>
                <th align="right">فرع 4</th>
                <th align="right">فرع 5</th>
                <th align="right">فرع 6</th>
            </tr>
            <tr>
                <th></th>
                <td align="right">صيانة BMW</td>
                <td align="right">صيانة BMW</td>
                <td align="right">صيانة سيارات أوروبية</td>
                <td align="right">سمكرة وبوية</td>
                <td align="right">قطع غيار مستعملة</td>
                <td align="right">صيانة سيارات أوروبية</td>
            </tr>
            <tr>
                <th align="right">الموقع</th>
                <td align="right">مخرج 18 صناعية الدائرى</td>
                <td align="right">حي الياسمين مخرج 5</td>
                <td align="right">مخرج 17 الصناعية القديمة</td>
                <td align="right">مخرج 18 بجوار الفرع الرئيسي</td>
                <td align="right">مخرج 17 حي السلي</td>
                <td align="right">مخرج 7 حي النرجس</td>
            </tr>
            <tr>
                <th align="right">الهاتف</th>
                <td align="right">0554773357</td>
                <td align="right">0557267766</td>
                <td align="right">0500681848</td>
                <td align="right">0508196341</td>
                <td align="right">0557367766</td>
                <td align="right">0554884558</td>
            </tr>

            </tbody>
        </table>
    </div>
</htmlpagefooter>
</body>
</html>
