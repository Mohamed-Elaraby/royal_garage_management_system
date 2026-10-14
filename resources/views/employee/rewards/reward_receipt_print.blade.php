<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
{{--    <title>{{ $employee_salary_log -> check_number }}.pdf</title>--}}
</head>
<style>
    /*.salary_table th, .salary_table tr, .salary_table td*/
    /*{*/
    /*    border: #0a0a0a 1px solid;*/
    /*    border-collapse: collapse;*/
    /*    text-align: center;*/
    /*    height: 50px;*/
    /*    vertical-align: middle;*/
    /*}*/
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
        font-size: 20px;
        border: 1px solid #000000;
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
<br>
<div class="body_content">
    <table class="" width="100%" style="text-align: center">
        <tr>
            <td width="50%" align="center">
                <div>
                    <h3>مركز الكراج الملكي</h3>
                    <h3>لصيانة السيارات الاوروبية</h3>
                </div>
{{--                <p>مركز اس كي بي للاصلاح الميكانيكي</p>--}}
{{--                <br>--}}
{{--                <p style="margin-right: 5px;">SKB AUTO MECHANICAL REPAIR L.L.C</p>--}}
            </td>
            <td width="50%" style="border-bottom: 1px solid #000; font-weight: bold; font-size: xx-large">سند استلام مكافاة</td>
            <td width="50%">
                <img src="storage/logo150x150.png" alt="logo">
            </td>
        </tr>
    </table>
    <br><br><br><br>
    <div class="receipt_body">
        <table style="width: 100%">
            <tr>
                <td>اقر انا الموظف:</td>
                <td>{{ $reward -> employee -> name }}</td>
            </tr>
            <tr>
                <td>اعمل في فرع:</td>
                <td>{{ $reward -> employee -> branch -> display_name }}</td>
            </tr>
            <tr>
                <td style="width: 30%">اننى استلمت مبلغ وقدره:</td>
                <td style="width: 70%">{{ $reward -> amount }} دولار</td>
            </tr>
            <tr>
                <td style="width: 30%">عن طريق :</td>
                <td style="width: 70%">{{ $reward -> payment_method }}</td>
            </tr>
            <tr>
                <td>وذلك عن:</td>
                <td>{{ $reward -> notes ? $reward -> notes : '-----------' }}</td>
            </tr>
            <tr>
                <td>بتاريخ:</td>
                <td>{{ $reward -> created_at -> format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <br><br><br><br>
    <div class="receipt_footer">
        <table style="width: 100%; text-align: center">
            <tr>
                <td style="width: 50%"><h6 class="text-center" style="font-weight: bold">توقيع المستلم</h6></td>
                <td style="width: 50%"><h6 class="text-center" style="font-weight: bold">الختم</h6></td>
            </tr>
            <tr>
                <td style="width: 50%" class="text-center">
{{--                    <img src="{{ asset('storage' . DIRECTORY_SEPARATOR . 'employees_signature' . DIRECTORY_SEPARATOR . 'reward_signature' . DIRECTORY_SEPARATOR . $reward -> id  . DIRECTORY_SEPARATOR . $employee_reward_signature -> image_name) }}" alt="" height="100" width="200">--}}
                    <p><img src="storage/employees_signature/reward_signature/{{ $reward -> id }}/{{ $employee_reward_signature -> image_name }}" alt="reward_signature" width="100"></p>

                </td>
                <td style="width: 50%"><p class="text-center">..........</p></td>
            </tr>
        </table>
    </div>
{{--    <table class="signatures" width="100%" style="text-align: center">--}}
{{--        <tr>--}}
{{--            <td width="50%">--}}
{{--                <p>توقيع المحاسب</p>--}}
{{--                <p>{{ auth()->user()->name }}</p>--}}
{{--            </td>--}}
{{--            <td width="50%">--}}
{{--                <br>--}}
{{--                <p>توقيع الموظف</p>--}}
{{--                <br>--}}
{{--                <p><img src="storage/employees_signature/salary_signature/{{ $employee_salary_log -> id }}/{{ $employee_salary_signature -> image_name }}" alt="employee_signature" width="100"></p>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--    </table>--}}
</div>
</body>
</html>
