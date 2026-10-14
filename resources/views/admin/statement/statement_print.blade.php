<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>يومية</title>
</head>
<style>
    .container {
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }
    @media (min-width: 768px) {
        .container {
            width: 750px;
        }
    }
    @media (min-width: 992px) {
        .container {
            width: 970px;
        }
    }
    @media (min-width: 1200px) {
        .container {
            width: 1170px;
        }
    }
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
    .no_wrapping
    {
        white-space: nowrap;
    }
    @page {
        header: page-header;
        footer: page-footer;
    }
</style>
<body>

<htmlpageheader name="page-header">
    <div class="header">
        <h2 class="lightgrey" style="text-align: center">
            يومية فرع
            [ {{ $branch -> display_name }} ]
            من تاريخ
            [ {{ \Carbon\Carbon::parse($startDate)->dayName . ' ' . $startDate }} ]
            حتى تاريخ
            [ {{ \Carbon\Carbon::parse($endDate)->dayName . ' ' . $endDate }} ]
        </h2>
    </div>
</htmlpageheader>

<div class="body_content">
    <div class="container">
        <table border="1" width="100%" style="font-size: 20px">

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
                @foreach($statements as $statement)
                    <tr>
                        <td>{{ $statement -> imports_cash }}</td>
                        <td>{{ $statement -> imports_network }}</td>
                        <td>{{ $statement -> imports_bank_transfer }}</td>
                        <td>{{ $statement -> card_details_hand_labour }}</td>
                        <td>{{ $statement -> card_details_new_parts }}</td>
                        <td>{{ $statement -> card_details_used_parts }}</td>
                        <td>{{ $statement -> card_details_tax }}</td>
                        <td>{{ $statement -> expenses_cash }}</td>
                        <td>{{ $statement -> expenses_network }}</td>
                        <td>{{ $statement -> custody_administration_cash }}</td>
                        <td>{{ $statement -> custody_administration_network }}</td>
                        <td>{{ $statement -> cash_to_administration }}</td>
                        <td>{{ $statement -> advances_and_salaries_cash }}</td>
                        <td>{{ $statement -> advances_and_salaries_network }}</td>
                        <td class="no_wrapping">{{ $statement -> notes }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th>{{ $total_imports_cash }}</th>
                    <th>{{ $total_imports_network }}</th>
                    <th>{{ $total_imports_bank_transfer }}</th>
                    <th>{{ $total_card_details_hand_labour }}</th>
                    <th>{{ $total_card_details_new_parts }}</th>
                    <th>{{ $total_card_details_used_parts }}</th>
                    <th>{{ $total_card_details_tax }}</th>
                    <th>{{ $total_expenses_cash }}</th>
                    <th>{{ $total_expenses_network }}</th>
                    <th>{{ $total_custody_administration_cash }}</th>
                    <th>{{ $total_custody_administration_network }}</th>
                    <th rowspan="2">{{ $total_cash_to_administration }}</th>
                    <th>{{ $total_advances_and_salaries_cash }}</th>
                    <th>{{ $total_advances_and_salaries_network }}</th>
                    <th rowspan="2">{{ __('trans.total') }}</th>
                </tr>

                <tr>
                    <th colspan="3">{{ $total_imports }}</th>
                    <th colspan="4">{{ $total_card_details }}</th>
                    <th colspan="2">{{ $total_expenses }}</th>
                    <th colspan="2">{{ $total_custody_administration }}</th>
                    <th colspan="2">{{ $total_advances_and_salaries }}</th>

                </tr>
            </tbody>
        </table>
        <div style="page-break-after: always;"></div> <!-- page break -->

        <table border="1" width="100%" style="font-size: 20px; background-color:#aeaeae">
            <tbody>
            <tr>
                <th colspan="2">تاريخ اليومية</th>
            </tr>
            <tr>
                <td width="30%">{{ $moneySafeOpeningBalance }}</td>
                <td width="70%">رصيد سابق</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_imports }}</td>
                <td width="70%">اجمالى الوارد</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_custody_administration_cash }}</td>
                <td width="70%">عهدة من الادارة</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_expenses_cash }}</td>
                <td width="70%">مصروفات</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_advances_and_salaries_cash }}</td>
                <td width="70%">سلف ورواتب</td>
            </tr>
            <tr>
                <td width="30%">{{ $total_cash_to_administration }}</td>
                <td width="70%">نقدى للادارة</td>
            </tr>
            <tr>
                @php($total_bank_transfer_and_network = $total_imports_network + $total_imports_bank_transfer)
                <td width="30%">{{ $total_bank_transfer_and_network }}</td>
                <td width="70%">بنك تحويل وشبكة</td>
            </tr>
            <tr>
                <td width="30%">
{{--                    {{--}}
{{--                        $moneySafeOpeningBalance +--}}
{{--                        $total_imports_cash ---}}
{{--                        $total_expenses +--}}
{{--                        $total_custody_administration ---}}
{{--                        $total_cash_to_administration ---}}
{{--                        $total_advances_and_salaries--}}
{{--                    }}--}}

                    {{
                        $moneySafeOpeningBalance +
                        $total_imports +
                        $total_custody_administration -
                        $total_expenses -
                        $total_advances_and_salaries -
                        $total_cash_to_administration -
                        $total_bank_transfer_and_network




                    }}
                </td>
                <td width="70%">الرصيد الحالى</td>
            </tr>

            </tbody>
        </table>
    </div>
</div>

<htmlpagefooter name="page-footer">
{{--    <hr style="border-top: 1px solid #000">--}}
    <div class="footer-info" style=" margin-top: 10px; ">
        Page {PAGENO} of {nb}
    </div>
</htmlpagefooter>
</body>
</html>
