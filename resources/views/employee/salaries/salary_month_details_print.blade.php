@php
    $month = \Illuminate\Support\Carbon::parse($employee_salary_log->salary_month)->month;
    $year = \Illuminate\Support\Carbon::parse($employee_salary_log->salary_month)->year;
@endphp

    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
{{--    <title>{{ $employee_salary_log -> check_number }}.pdf</title>--}}
</head>
<style>
    .salary_table th, .salary_table tr, .salary_table td
    {
        border: #0a0a0a 1px solid;
        border-collapse: collapse;
        text-align: center;
        height: 50px;
        vertical-align: middle;
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
        padding: 20px;
        text-align: center;
        width: 200px;
        margin: 0 auto;
        font-size: 20px;
    }
    body {

        direction: rtl;
        font-size: 16px;
    }
    @page {
        header: page-header;
        footer: page-footer;
    }
    @media print {
        .signatures td img{
            border: #9e0505 1px solid;
            height: 100px;
        }
    }
</style>
<body>

<htmlpageheader name="page-header">
    <div class="header">
        <table width="100%">
            <tr>
                <td width="40%">
                    <img src="storage/logo150x150.png" alt="logo">
                </td>
                <td width="60%">
                    <div class="company_name_inside_elements">
                        <h2>مركز اس كي بي للاصلاح الميكانيكي</h2>
                        <br>
                        <h3 style="margin-right: 5px;">SKB AUTO MECHANICAL REPAIR L.L.C</h3>
                    </div>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr>
                <td class="lightgrey" width="100%" style="text-align: center;"><h5 class="repair_order">بطاقة راتب</h5></td>
            </tr>
            <tr>
                <td class="lightgrey" width="100%" style="text-align: center;"><h5 class="repair_order"> شهر {{ $month .'-'. $year }} </h5></td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr class="lightgrey">
                <th style="text-align: right" width="25%">بيانات الموظف</th>
                <th style="text-align: left" width="25%" colspan="2">Employee Information</th>
            </tr>

            <tr><!-- Row 1 -->
                <td width="25%" style="text-align: right">الاسم :</td>
                <td width="50%" style="text-align: center">{{ $employee_salary_log -> employee -> name }}</td>
                <td width="25%" style="text-align: left">: Name</td>
            </tr>

            <tr><!-- Row 2 -->
                <td width="25%" style="text-align: right">الفرع :</td>
                <td width="50%" style="text-align: center">{{ $employee_salary_log -> employee -> branch ->name }}</td>
                <td width="25%" style="text-align: left">: Branch</td>

            </tr>

        </table>
        <hr><br>
        <h5 class="lightgrey terms_of_repair_title">تاريخ القبض : {{ \Carbon\Carbon::parse($employee_salary_log->updated_at)->format('d-m-Y') }}</h5>
    </div>
</htmlpageheader>
<br>
<div class="body_content">
    <table class="salary_table" width="100%">
        <tr>
            <td class="lightgrey">{{ __('trans.main') }}</td>
            <td class="lightgrey">{{ __('trans.housing allowance') }}</td>
            <td class="lightgrey">{{ __('trans.transfer allowance') }}</td>
            <td class="lightgrey">{{ __('trans.travel allowance') }}</td>
            <td class="lightgrey">{{ __('trans.other allowance') }}</td>
            <td class="lightgrey">{{ __('trans.description of other allowance')}}</td>
            <td class="lightgrey">{{ __('trans.end service allowance') }}</td>
            <td class="lightgrey">{{ __('trans.total') }}</td>
        </tr>
        <tr>
            <td>{{ $employee_salary_log -> main ?? 0 }}</td>
            <td>{{ $employee_salary_log -> housing_allowance ?? 0 }}</td>
            <td>{{ $employee_salary_log -> transfer_allowance ?? 0 }}</td>
            <td>{{ $employee_salary_log -> travel_allowance ?? 0 }}</td>
            <td>{{ $employee_salary_log -> other_allowance ?? 0 }}</td>
            <td>{{ $employee_salary_log -> description_of_other_allowance ?? 0 }}</td>
            <td>{{ $employee_salary_log -> end_service_allowance ?? 0 }}</td>
            <td>{{ $employee_salary_log -> total_salary ?? 0 }}</td>
        </tr>
        <tr>
            <td class="lightgrey">{{ __('trans.total advances') }}</td>
            <td class="lightgrey">{{ __('trans.total rewards') }}</td>
            <td class="lightgrey">{{ __('trans.total vacations') }}</td>
            <td class="lightgrey">{{ __('trans.total discounts') }}</td>
        </tr>
        <tr>
            <td>{{ $employee_salary_log -> total_advances ?? 0 }}</td>
            <td>{{ $employee_salary_log -> total_rewards ?? 0 }}</td>
            <td>{{ $employee_salary_log -> total_vacations ?? 0 }}</td>
            <td>{{ $employee_salary_log -> total_discounts ?? 0 }}</td>
        </tr>
        <tr>
            <td class="lightgrey" style="font-weight: bold">{{ __('trans.final salary') }}</td>
            <td class="lightgrey" style="font-weight: bold">{{ $employee_salary_log -> final_salary ?? 0 }}</td>
        </tr>
    </table>
    <hr><br>
    <table class="signatures" width="100%" style="text-align: center">
        <tr>
            <td width="50%">
                <p>توقيع المحاسب</p>
                <p>{{ auth()->user()->name }}</p>
            </td>
            <td width="50%">
                <br>
                <p>توقيع الموظف</p>
                <br>
                <p><img src="storage/employees_signature/salary_signature/{{ $employee_salary_log -> id }}/{{ $employee_salary_signature -> image_name }}" alt="employee_signature" width="100"></p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
