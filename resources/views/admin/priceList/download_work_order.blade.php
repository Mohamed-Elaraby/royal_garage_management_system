<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>work order - {{ $price_list -> chassis_number }}.pdf</title>
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
                    <td align="center" colspan="2">امر عمل</td>
                </tr>
                <tr>
                    <td align="center" colspan="2">Work Order</td>
                </tr>
            </table>
            <br><br>

            <table width="100%">
                <tr>
                    <td align="center" width="20%">
                        <div>وقت اصدار</div><br>
                        <div>تاريخ اصدار</div><br>
                        <div>الفرع</div><br>
                        <div>الموظف</div><br>
                        <div>رقم الشاسية</div><br>
                    </td>
                    <td align="center" width="20%">
                        <div>{{ $price_list -> created_at -> format('h:i:s a') }}</div><br>
                        <div> {{ $price_list -> created_at -> format('d/m/Y') }}</div><br>
                        <div>{{ $price_list -> branch -> display_name }}</div><br>
                        <div>{{ $price_list -> user -> name }}</div><br>
                        <div>{{ $price_list -> chassis_number }}</div><br>
                    </td>
                    <td align="center" width="20%">
                        <div>Invoice Issue Time</div><br>
                        <div>Invoice Issue Date</div><br>
                        <div>Branch</div><br>
                        <div>Employee</div><br>
                        <div>chassis_number</div><br>
                    </td>
                    <td align="left" width="20%">
                        <div class="logo">
                            <img src="storage/logo150x150.png" alt="logo">
                        </div> <!-- end logo -->
                    </td>
                </tr>
            </table>
            <br><br>
        </div>
    </htmlpageheader>

    <div class="body">
        <table class="items" width="100%">
            <tr class="lightgrey">
                <th align="center">
                    <p>تفاصيل السلع او الخدمات</p>
                    <p>Goods Of Nature Or Services</p>
                </th>
                <th align="center">
                    <p>الكمية</p>
                    <p>Quantity</p>
                </th>


            </tr>
            @foreach ($price_list -> priceListProducts as $product)
                <tr>
                    <td align="center">{{ $product -> item_name }}</td>

                    <td align="center">{{ $product -> item_quantity }}</td>
                </tr>
            @endforeach


        </table>
        @if ($price_list -> notes)
            <div class="notes" style="padding-top: 20px">
                <table class="notes_table" width="100%">
                    <tr>
                        <th align="center">ملاحظات</th>
                        <td align="center">{{ $price_list -> notes }}</td>
                    </tr>
                </table>
            </div>
        @endif
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
