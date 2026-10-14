<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $check -> check_number }}.pdf</title>
</head>
<style>
    .items, .items th, .items td,
    .notes_table, .notes_table th, .notes_table td
    {
        border: #0a0a0a 1px solid;
        border-collapse: collapse;
    }
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
    <div class="header">
        <table width="100%" class="lightgrey">
            <tr>
                <td align="center" colspan="2">بطاقة عمل</td>
            </tr>
            <tr>
                <td align="center" colspan="2">Job Card</td>
            </tr>
            <tr>
                <td align="center" colspan="2">Repair Order: {{ $check -> check_number }}</td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr class="lightgrey">
                <th style="text-align: right" width="25%">بيانات العميل</th>
                <th style="text-align: left" width="25%" colspan="2">Client Info</th>
                <th style="text-align: right" width="25%">بيانات المورد</th>
                <th style="text-align: left" width="25%" colspan="2">Supplier Info</th>
            </tr>

            <tr><!-- Row 1 -->
                <td width="16.66%" style="text-align: right">الاسم :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> client -> name }}</td>
                <td width="16.66%" style="text-align: left">: Name</td>
                <td width="16.66%" style="text-align: right">الاسم :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> branch -> name }}</td>
                <td width="16.66%" style="text-align: left">: Name</td>
            </tr>
            <tr> <!-- Row 2 -->
                <td width="16.66%" style="text-align: right">رقم المبنى :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> client -> building_number }}</td>
                <td width="16.66%" style="text-align: left">: Building No</td>
                <td width="16.66%" style="text-align: right">رقم المبنى :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> branch -> building_number }}</td>
                <td width="16.66%" style="text-align: left">: Building No</td>
            </tr>
            <tr> <!-- Row 3 -->
                <td width="16.66%" style="text-align: right">اسم الشارع :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> client -> street_name }}</td>
                <td width="16.66%" style="text-align: left">: Street Name</td>
                <td width="16.66%" style="text-align: right">اسم الشارع :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> branch -> street_name }}</td>
                <td width="16.66%" style="text-align: left">: Street Name</td>
            </tr>
            <tr> <!-- Row 4 -->
                <td width="16.66%" style="text-align: right">رقم الهاتف :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> client -> phone }}</td>
                <td width="16.66%" style="text-align: left">: Phone Number</td>
                <td width="16.66%" style="text-align: right">رقم الهاتف :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> branch -> phone }}</td>
                <td width="16.66%" style="text-align: left">: Phone Number</td>
            </tr>
            <tr> <!-- Row 5 -->
                <td width="16.66%" style="text-align: right">الحي :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> client -> district }}</td>
                <td width="16.66%" style="text-align: left">: District</td>
                <td width="16.66%" style="text-align: right">الحي :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> branch -> district }}</td>
                <td width="16.66%" style="text-align: left">: District</td>
            </tr>
            <tr> <!-- Row 6 -->
                <td width="16.66%" style="text-align: right">المدينة :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> client -> city }}</td>
                <td width="16.66%" style="text-align: left">: City</td>
                <td width="16.66%" style="text-align: right">المدينة :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> branch -> city }}</td>
                <td width="16.66%" style="text-align: left">: City</td>
            </tr>
            <tr> <!-- Row 7 -->
                <td width="16.66%" style="text-align: right">البلد :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> client -> country }}</td>
                <td width="16.66%" style="text-align: left">: Country</td>
                <td width="16.66%" style="text-align: right">البلد :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> branch -> country }}</td>
                <td width="16.66%" style="text-align: left">: Country</td>
            </tr>
            <tr> <!-- Row 8 -->
                <td width="16.66%" style="text-align: right">الرمز البريدى :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> client -> postal_code }}</td>
                <td width="16.66%" style="text-align: left">: Postal Code</td>
                <td width="16.66%" style="text-align: right">الرمز البريدى :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> branch -> postal_code }}</td>
                <td width="16.66%" style="text-align: left">: Postal Code</td>
            </tr>
            <tr> <!-- Row 9 -->
                <td width="16.66%" style="text-align: right">رقم تسجيل ضريبة القيمة المضافة :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> client -> vat_number }}</td>
                <td width="16.66%" style="text-align: left">: Vat Number</td>
                <td width="16.66%" style="text-align: right">رقم تسجيل ضريبة القيمة المضافة :</td>
                <td width="16.66%" style="text-align: center">{{ $check -> branch -> vat_number }}</td>
                <td width="16.66%" style="text-align: left">: Vat Number</td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr class="lightgrey">
                <td colspan="2" style="text-align: right" width="12.5">رقم العداد</td>
                <td style="text-align: left" width="12.5" colspan="2">Counter No</td>
                <td colspan="2" style="text-align: right" width="12.5">رقم الشاسية</td>
                <td style="text-align: left" width="12.5" colspan="2">Chassis No</td>
                <td colspan="2" style="text-align: right" width="12.5">رقم اللوحة</td>
                <td style="text-align: left" width="12.5" colspan="2">Plate No</td>
                <td colspan="2" style="text-align: right" width="12.5">لون السيارة</td>
                <td style="text-align: left" width="12.5" colspan="2">Car color</td>
            </tr>

            <tr><!-- Row 1 -->
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> counter_number }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> chassis_number }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> plate_number }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> car_color }}</td>
            </tr>

            <tr class="lightgrey">
                <td colspan="2" style="text-align: right" width="12.5">النوع</td>
                <td style="text-align: left" width="12.5" colspan="2">Type</td>
                <td colspan="2" style="text-align: right" width="12.5">حجم السيارة</td>
                <td style="text-align: left" width="12.5" colspan="2">Car size</td>
                <td colspan="2" style="text-align: right" width="12.5">الموديل</td>
                <td style="text-align: left" width="12.5" colspan="2">Model</td>
                <td colspan="2" style="text-align: right" width="12.5">رقم المحرك</td>
                <td style="text-align: left" width="12.5" colspan="2">Engine No</td>
            </tr>

            <tr><!-- Row 2 -->
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> car_type }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> car_size }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> car_model }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> car_engine }}</td>
            </tr>

            <tr class="lightgrey">
                <td colspan="2" style="text-align: right" width="12.5">كود التطوير</td>
                <td style="text-align: left" width="12.5" colspan="2">Dev Code</td>
                <td colspan="2" style="text-align: right" width="12.5">مستوى البنزين</td>
                <td style="text-align: left" width="12.5" colspan="2">Fuel level</td>
                <td colspan="2" style="text-align: right" width="12.5">اسم المحرر</td>
                <td style="text-align: left" width="12.5" colspan="2">Created by</td>
                <td colspan="2" style="text-align: right" width="12.5">اسم السائق</td>
                <td style="text-align: left" width="12.5" colspan="2">Driver name</td>
            </tr>

            <tr><!-- Row 2 -->
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> car_development_code }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> fuel_level }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> user? $check -> user -> name: '' }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> driver_name }}</td>
            </tr>

            <tr class="lightgrey">
                <td colspan="2" style="text-align: right" width="12.5">تاريخ الدخول</td>
                <td style="text-align: left" width="12.5" colspan="2">Date in</td>
                <td colspan="2" style="text-align: right" width="12.5">وقت الدخول</td>
                <td style="text-align: left" width="12.5" colspan="2">Time in</td>
                <td colspan="2" style="text-align: right" width="12.5">تاريخ الخروج</td>
                <td style="text-align: left" width="12.5" colspan="2">Date out</td>
                <td colspan="2" style="text-align: right" width="12.5">وقت الخروج</td>
                <td style="text-align: left" width="12.5" colspan="2">Time out</td>
            </tr>

            <tr><!-- Row 2 -->
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> created_at -> format('d/m/Y') }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> created_at -> format('h:i:s a') }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> car_exit_date ? $check -> car_exit_date -> format('d/m/Y') :'' }}</td>
                <td colspan="4" width="25%" style="text-align: center">{{ $check -> car_exit_date ? $check -> car_exit_date -> format('h:i:s a'):'' }}</td>
            </tr>
        </table>
    </div>
</htmlpageheader>

<div class="body_content">
    <div class="repair_needed">
        <h5 class="lightgrey repair_needed_title">الإصلاحات المطلوبة</h5>
        <span class="repair_needed_content">
                {!! $check -> car_status_report !!}
            </span>
    </div> <!-- end repair needed -->
    <br>
    @if ($check -> car_status_report_note)
        <div class="col-xs-12 padding-0">
            <div class="car_status_report_note">
                <h5 class="lightgrey car_status_report_note_title"> ملاحظات علي الإصلاحات المطلوبة</h5>
                <span class="car_status_report_note_content">
                        {!! $check -> car_status_report_note !!}
                    </span>
            </div>
        </div> <!-- end col 12 -->
    @endif
    <br>
    @if ($check -> car_images_note)
        <div class="col-xs-12 padding-0">
            <div class="car_images_note">
                <h5 class="lightgrey car_images_note_title">ملاحظات علي صور السيارة</h5>
                <span class="car_images_note_content" >
                    {!! $check -> car_images_note !!}
                </span>
            </div>
        </div> <!-- end col 12 -->
    @endif
    <br>
    <div style="page-break-after: always;"></div> <!-- page break -->
    <div class="terms_of_repair">
        <h5 class="lightgrey terms_of_repair_title">شروط التصليح</h5>
        <span class="terms_of_repair_content">
                <div dir="rtl" lang="ar-sa">
                    @include('admin.includes.terms_of_repair')
                </div>

                <hr style="border-top: 1px solid #000; width: 300px; margin: 0 auto;">
                أقر ان جميع الأعمال المبينة أعلاه وقد أنجزت وقد استلمت السيارة بحالة جيدة
                <br>
            <table width="100%">
                <tr>
                        <td align="center" width="50%">توقيع العميل عند دخول السيارة</td>
                        <td align="center" width="50%">توقيع العميل عند خروج السيارة</td>
                </tr>
                <tr>
                    @if ($client_signature_entry)
                        <td align="center" width="50%"><img src="storage/check_cars/signature_entry/{{$check->check_number}}/{{$client_signature_entry -> image_name}}" alt="" height="100" width="200"></td>
                    @endif
                    @if ($client_signature_exit)
                        <td align="center" width="50%"><img src="storage/check_cars/signature_exit/{{$check->check_number}}/{{$client_signature_exit -> image_name}}" alt="" height="100" width="200"></td>
                    @endif

                </tr>
            </table>
        </span>
    </div> <!-- end terms_of_repair -->

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
                <td align="right">0508196341</td>
                <td align="right">0557367766</td>
                <td align="right">0500681848</td>
                <td align="right">0554884558</td>
            </tr>

            </tbody>
        </table>
    </div>
</htmlpagefooter>
</body>
</html>
