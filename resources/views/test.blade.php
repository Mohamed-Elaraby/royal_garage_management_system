<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>يومية</title>
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

    </div>
</htmlpageheader>

<div class="body_content">
{{--    <table border="1">
        <tr>
            <td colspan="3">الوارد</td>
            <td colspan="4">تفاصيل الكارت</td>
            <td rowspan="2">البيان</td>
        </tr>
        <tr>
            <td>كاش</td>
            <td>شبكة</td>
            <td>تحويل</td>
            <td>اجور اليد</td>
            <td>قطع جديدة</td>
            <td>قطع مستعملة</td>
            <td>ضريبة 15%</td>
        </tr>

    </table>--}}
    <table border="1" width="100%">

        <colgroup style="background-color:#56bffa">
            <col>
            <col>
            <col>
        </colgroup>
        <colgroup style="background-color:#aeaeae">
            <col>
            <col>
            <col>
            <col>
        </colgroup>
        <colgroup style="background-color:#56bffa">
            <col>
            <col>
        </colgroup>
        <colgroup style="background-color:#aeaeae">
            <col>
            <col>
        </colgroup>
        <col style="background-color:#56bffa">
        <colgroup style="background-color:#aeaeae">
            <col>
            <col>
        </colgroup>
        <col>
        <thead>
            <tr>
                <th colspan="3">الوارد</th>
                <th colspan="4">تفاصيل الكارت</th>
                <th colspan="2">مصروفات</th>
                <th colspan="2">عهدة من الادارة</th>
                <th rowspan="2">نقدى الى الادارة</th>
                <th colspan="2">سلف ورواتب</th>
                <th rowspan="2">البيان</th>
            </tr>
            <tr>
                <td>كاش</td>
                <td>شبكة</td>
                <td>تحويل</td>

                <td>اجور اليد</td>
                <td>قطع جديدة</td>
                <td>قطع مستعملة</td>
                <td>ضريبة 15%</td>

                <td>كاش</td>
                <td>شبكة</td>

                <td>كاش</td>
                <td>شبكة</td>


                <td>كاش</td>
                <td>شبكة</td>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td>350</td>
                <td></td>
                <td></td>
                <td>150</td>
                <td></td>
                <td>200</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>حساب سيارة BMW 730I رقم 55869</td>
            </tr>
        </tbody>

    </table>
</div>

<htmlpagefooter name="page-footer">
    <hr style="border-top: 1px solid #000">
    <div class="footer-info" style=" margin-top: 10px; ">
        <table class="table table-borderless" align="center">
            <tbody></tbody>
        </table>
    </div>
</htmlpagefooter>
</body>
</html>
