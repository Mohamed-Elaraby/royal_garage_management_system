@extends('admin.layouts.app')

@section('title',  $purchase_order -> invoice_number.'.pdf')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="buttons_area">
{{--            <button id="printBTN" class="btn btn-primary btn-sm btn-block" onclick="window.print()" >--}}
{{--                <span>طباعة <i class="fa fa-print"></i></span>--}}
{{--            </button>--}}

            <!-- tax invoice -->
            <button
                id="tax_invoice_print"
                class="btn btn-primary btn-sm btn-block"
                data-link="{{ route('admin.purchaseOrder.tax_invoice', ['purchase_order_id' => $purchase_order->id]) }}"
                href="javascript:void(0);"
                onclick="printJS($(this).data('link'))"
            >
                <span> طباعة الفاتورة <i class="fa fa-print"></i></span>
            </button>

            <a href="{{ route('admin.purchaseOrders.index', ['status' => 'close']) }}" class="btn btn-warning btn-sm btn-block">
                <span>الذهاب الى صفحة فواتير الشراء <i class="fa fa-backward"></i></span>
            </a>

        </div>
        <div class="container">
            <div class="report">

                <table class="report-container">
                    <thead class="report-header">
                    <tr>
                        <td class="report-header-cell">
                            <div class="t-page-header">
                                <div class="header">
                                    <table width="100%" class="lightgrey">
                                        <tr>
                                            <td align="center" colspan="2">فاتورة ضريبية</td>
                                        </tr>
                                        <tr>
                                            <td align="center" colspan="2">Tax Invoice</td>
                                        </tr>
                                    </table>
                                    <br><br>
                                    @php
                                        $seller_name                = 'مركز الكراج الملكي لصيانة السيارات';
                                        $vat_number                 = '300014551300003';
                                        $invoice_date               = $purchase_order -> created_at -> format('d/m/Y - h:i:s');
                                        $total_invoice_amount       = $purchase_order -> total_amount_due;
                                        $total_vat                  = $purchase_order -> total_vat;
                                        $generatedString = \App\Traits\HelperTrait::SETQRCODE($seller_name, $vat_number, $invoice_date, $total_invoice_amount,$total_vat);
                                    @endphp
                                    <table width="100%">
                                        <tr>
                                            <td width="25%" rowspan="5">
                                                <img src="{{ $generatedString }}" width="150">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right">رقم الفاتورة</td>
                                            <td align="center">{{ $purchase_order -> invoice_number }}</td>
                                            <td align="left">Invoice Number</td>
                                        </tr>
                                        <tr>
                                            <td align="right">وقت اصدار الفاتورة</td>
                                            {{--                <td align="center">{{ $purchase_order -> invoice_date }}</td>--}}
                                            <td align="center" style="direction: ltr"> {{ $purchase_order -> created_at -> format('h:i:s a') }}</td>
                                            <td align="left">Invoice Issue Time</td>
                                        </tr>
                                        <tr>
                                            <td align="right">تاريخ اصدار الفاتورة</td>
                                            <td align="center"> {{ $purchase_order -> created_at -> format('d/m/Y') }}</td>
                                            <td align="left">Invoice Issue Date</td>
                                        </tr>
                                        <tr>
                                            <td align="right">تاريخ التوريد</td>
                                            <td align="center"> {{ $purchase_order -> created_at -> format('d/m/Y') }}</td>
                                            <td align="left">Date Of Supply</td>
                                        </tr>

                                    </table>
                                    <br><br>
                                    <table class="header_table">
                                        <tr class="lightgrey">
                                            <th colspan="3" style="width: 50%;">
                                                <span style="direction: rtl">
                                                    بيانات العميل
                                                </span>
                                                <span style="direction: ltr; float: left">
                                                    Client Info
                                                </span>
                                            </th>
                                            <th colspan="3" style="width: 50%;">
                                                <span style="direction: rtl">
                                                   بيانات المورد
                                                </span>
                                                <span style="direction: ltr; float: left">
                                                    Supplier Info
                                                </span>
                                            </th>
                                        </tr>

                                        <tr><!-- Row 1 -->
                                            <td style="text-align: right">الاسم :</td>
                                            <td style="text-align: center">{{ $purchase_order -> supplier -> name }}</td>
                                            <td style="text-align: left">: Name</td>
                                            <td style="text-align: right">الاسم :</td>
                                            <td style="text-align: center">مركز الكراج الملكي لصيانة السيارات</td>
                                            <td style="text-align: left">: Name</td>
                                        </tr>
                                        <tr> <!-- Row 2 -->
                                            <td style="text-align: right">رقم المبنى :</td>
                                            <td style="text-align: center">{{ $purchase_order -> supplier -> building_number }}</td>
                                            <td style="text-align: left">: Building No</td>
                                            <td style="text-align: right">رقم المبنى :</td>
                                            <td style="text-align: center">7158</td>
                                            <td style="text-align: left">: Building No</td>
                                        </tr>
                                        <tr> <!-- Row 3 -->
                                            <td style="text-align: right">اسم الشارع :</td>
                                            <td style="text-align: center">{{ $purchase_order -> supplier -> street_name }}</td>
                                            <td style="text-align: left">: Street Name</td>
                                            <td style="text-align: right">اسم الشارع :</td>
                                            <td style="text-align: center">نجران</td>
                                            <td style="text-align: left">: Street Name</td>
                                        </tr>
                                        <tr> <!-- Row 4 -->
                                            <td style="text-align: right">رقم الهاتف :</td>
                                            <td style="text-align: center">{{ $purchase_order -> supplier -> phone }}</td>
                                            <td style="text-align: left">: Phone Number</td>
                                            <td style="text-align: right">رقم الهاتف :</td>
                                            <td style="text-align: center">0554773357</td>
                                            <td style="text-align: left">: Phone Number</td>
                                        </tr>
                                        <tr> <!-- Row 5 -->
                                            <td style="text-align: right">الحي :</td>
                                            <td style="text-align: center">{{ $purchase_order -> supplier -> district }}</td>
                                            <td style="text-align: left">: District</td>
                                            <td style="text-align: right">الحي :</td>
                                            <td style="text-align: center">الفيصلية</td>
                                            <td style="text-align: left">: District</td>
                                        </tr>
                                        <tr> <!-- Row 6 -->
                                            <td style="text-align: right">المدينة :</td>
                                            <td style="text-align: center">{{ $purchase_order -> supplier -> city }}</td>
                                            <td style="text-align: left">: City</td>
                                            <td style="text-align: right">المدينة :</td>
                                            <td style="text-align: center">الرياض</td>
                                            <td style="text-align: left">: City</td>
                                        </tr>
                                        <tr> <!-- Row 7 -->
                                            <td style="text-align: right">البلد :</td>
                                            <td style="text-align: center">{{ $purchase_order -> supplier -> country }}</td>
                                            <td style="text-align: left">: Country</td>
                                            <td style="text-align: right">البلد :</td>
                                            <td style="text-align: center">المملكة العربية السعودية</td>
                                            <td style="text-align: left">: Country</td>
                                        </tr>
                                        <tr> <!-- Row 8 -->
                                            <td style="text-align: right">الرمز البريدى :</td>
                                            <td style="text-align: center">{{ $purchase_order -> supplier -> postal_code }}</td>
                                            <td style="text-align: left">: Postal Code</td>
                                            <td style="text-align: right">الرمز البريدى :</td>
                                            <td style="text-align: center">12896 - 3855</td>
                                            <td style="text-align: left">: Postal Code</td>
                                        </tr>
                                        <tr> <!-- Row 9 -->
                                            <td style="text-align: right">رقم تسجيل ضريبة القيمة المضافة :</td>
                                            <td style="text-align: center">{{ $purchase_order -> supplier -> vat_number }}</td>
                                            <td style="text-align: left">: Vat Number</td>
                                            <td style="text-align: right">رقم تسجيل ضريبة القيمة المضافة :</td>
                                            <td style="text-align: center">300014551300003</td>
                                            <td style="text-align: left">: Vat Number</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <tbody class="report-content">
                    <tr>
                        <td class="report-content-cell">
                            <div class="main">
                                <div class="body" style="padding-top: 20px">
                                    <table class="items" width="100%">
                                        <tr class="lightgrey">
                                            <th align="center">
                                                <p>الكود</p>
                                                <p>Code</p>
                                            </th>
                                            <th class="text-center">
                                                <p>تفاصيل السلع او الخدمات</p>
                                                <p>Goods Of Nature Or Services</p>
                                            </th>
                                            <th class="text-center">
                                                <p>سعر الوحدة</p>
                                                <p>price Unit</p>
                                            </th>
                                            <th class="text-center">
                                                <p>الكمية</p>
                                                <p>Quantity</p>
                                            </th>
                                            <th class="text-center">
                                                <p>المبلغ الخاضع للضريبة</p>
                                                <p>Amount Taxable</p>
                                            </th>
                                            <th class="text-center">
                                                <p>الخصم</p>
                                                <p>Discount</p>
                                            </th>
                                            <th class="text-center">
                                                <p>نسبة الضريبة</p>
                                                <p>Tax Rate</p>
                                            </th>
                                            <th class="text-center">
                                                <p>مبلغ الضريبة</p>
                                                <p>Tax Amount</p>
                                            </th>
                                            <th class="text-center">
                                                <p>المبلغ شامل ضريبة القيمة المضافة</p>
                                                <p>(Item Subtotal (Including VAT</p>
                                            </th>
                                        </tr>
                                        @foreach ($purchase_order -> purchaseOrderProducts as $product)
                                            <tr>
                                                <td align="center">{{ $product -> item_code }}</td>
                                                <td align="center">{{ $product -> item_name }}</td>
                                                <td align="center">{{ $product -> item_price }} دولار</td>
                                                <td align="center">{{ $product -> item_quantity }}</td>
                                                <td align="center">{{ $product -> item_amount_taxable }} دولار</td>
                                                <td align="center">{{ $product -> item_discount }} {{ $product -> item_discount ? $product -> item_discount_type == 0 ? ' دولار ' : ' % ' : ''}}</td>
                                                <td align="center">{{ $product -> item_tax_amount > 0 ? '15%' : '0%' }}</td>
                                                <td align="center">{{ $product -> item_tax_amount }} دولار</td>
                                                <td align="center">{{ $product -> item_sub_total }} دولار</td>
                                            </tr>
                                        @endforeach
                                        <tr class="lightgrey">
                                            <th colspan="4" align="right">اجمالى المبالغ</th>
                                            <th colspan="4" class="text-center">Total Amounts</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">الاجمالى (غير شامل ضريبة القيمة المضافة)</td>
                                            <td colspan="4" class="text-center"> ( Total ( Excluding VAT</td>
                                            <td align="center">{{ $purchase_order -> total }} دولار</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">مجموع الخصومات</td>
                                            <td colspan="4" class="text-center">Discount</td>
                                            <td align="center">{{ $purchase_order -> discount }} دولار</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">الاجمالى الخاضع للضريبة (غير شامل ضريبة القيمة المضافة)</td>
                                            <td colspan="4" class="text-center"> ( Total Taxable Amount ( Excluding VAT</td>
                                            <td align="center">{{ $purchase_order -> taxable_amount }} دولار</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">مجموع ضريبة القيمة المضافة</td>
                                            <td colspan="4" class="text-center">Total VAT</td>
                                            <td align="center">{{ $purchase_order -> total_vat }} دولار</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">اجمالى المبلغ المستحق</td>
                                            <td colspan="4" class="text-center">Total Amount Due</td>
                                            <td align="center">{{ $purchase_order -> total_amount_due }} دولار</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">خصم المورد</td>
                                            <td colspan="4" class="text-center">Supplier Discount</td>
                                            <td align="center">{{ $purchase_order -> supplier_discount_amount ?? 0 }} دولار</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">المبلغ المدفوع</td>
                                            <td colspan="4" class="text-center">Amount Paid</td>
                                            <td align="center">{{ $purchase_order -> amount_paid + $purchase_order -> amount_paid_bank }} دولار</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">المبلغ المتبقي</td>
                                            <td colspan="4" class="text-center">Amount Due</td>
                                            <td align="center">{{ $purchase_order -> amount_due }} دولار</td>
                                        </tr>
                                    </table>
                                    @if ($purchase_order -> notes)
                                        <div class="notes" style="padding-top: 20px">
                                            <table class="notes_table" width="100%">
                                                <tr>
                                                    <th align="center">ملاحظات</th>
                                                    <td align="center">{{ $purchase_order -> notes }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div> <!-- end main -->
                        </td>
                    </tr>
                    </tbody>
                    <tfoot class="report-footer">
                    <tr>
                        <td class="report-footer-cell">
                            <div class="footer-info" style=" margin-top: 10px; ">
                                <div class="t-page-footer">
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
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection
@push('links')
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- RTL Files -->
    <link rel="stylesheet" href="{{ asset('assets/dist/rtl/css/bootstrap.rtl.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/receipt/css/orders.css') }}">

    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">

    <style>
        .items, .items th, .items td,
        .notes_table, .notes_table th, .notes_table td
        {
            border: #0a0a0a 1px solid;
            border-collapse: collapse;
        }

        .footer-info td {
            padding-left: 10px;
        }

        .lightgrey {
            background-color: lightgrey;
        }

        .report-container {
            page-break-after: always;
        }

        .repair_order {
            padding: 5px 2px;
            border: 1px solid #000;
            text-align: center;
            width: 200px;
            margin: 0 auto;
        }

        .row>* {
            direction: rtl !important;
            font-size: 10px !important;
        }

        @font-face {
            font-family: myFirstFont;
            src: url({{ asset('assets/dist/fonts/sst-arabic-medium.ttf') }});
        }

        .header_table {
            table-layout: fixed;
            width: 100%;
        }

        .header_table td, .header_table th {
            word-wrap: break-word;
            /*border: #000000 solid 1px;*/
            padding: 3px;
        }

        .report-footer {
            /*position: absolute;*/
            /*bottom: 0;*/
        }
    </style>
@endpush

@push('scripts')
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

    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

@endpush

