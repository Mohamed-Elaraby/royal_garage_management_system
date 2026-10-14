@extends('admin.layouts.app')

@section('title',  $purchase_order_returns -> invoice_number.'.pdf')

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="buttons_area">

                <!-- Simplified tax invoice -->
{{--                <button--}}
{{--                    id="tax_invoice_print"--}}
{{--                    class="btn btn-success btn-sm btn-block"--}}
{{--                    data-link="{{ route('admin.simplified_tax_invoice', ['purchase_order_id' => $purchase_order_returns->id]) }}"--}}
{{--                    href="javascript:void(0);"--}}
{{--                    onclick="printJS($(this).data('link'))"--}}
{{--                >--}}
{{--                    <span> طباعة الفاتورة المبسطة <i class="fa fa-print"></i></span>--}}
{{--                </button>--}}

{{--                <a target="_blank"--}}
{{--                   href="{{ route('admin.simplified_tax_invoice', ['purchase_order_id' => $purchase_order_returns->id]) }}"--}}
{{--                   class="btn btn-success btn-sm btn-block">--}}
{{--                    <span> عرض الفاتورة المبسطة ك PDF <i class="fa fa-paperclip"></i></span>--}}
{{--                </a>--}}

{{--                <a href="{{ route('admin.simplified_tax_invoice', ['purchase_order_id' => $purchase_order_returns->id, 'download' => true]) }}"--}}
{{--                   class="btn btn-success btn-sm btn-block">--}}
{{--                    <span> تحميل الفاتورة المبسطة <i class="fa fa-download"></i></span>--}}
{{--                </a>--}}

{{--                <!-- tax invoice -->--}}
{{--                <button--}}
{{--                    id="tax_invoice_print"--}}
{{--                    class="btn btn-primary btn-sm btn-block"--}}
{{--                    data-link="{{ route('admin.tax_invoice', ['purchase_order_id' => $purchase_order_returns->id]) }}"--}}
{{--                    href="javascript:void(0);"--}}
{{--                    onclick="printJS($(this).data('link'))"--}}
{{--                >--}}
{{--                    <span> طباعة الفاتورة <i class="fa fa-print"></i></span>--}}
{{--                </button>--}}

{{--                <a target="_blank" href="{{ route('admin.tax_invoice', ['purchase_order_id' => $purchase_order_returns->id]) }}"--}}
{{--                   class="btn btn-primary btn-sm btn-block">--}}
{{--                    <span> عرض الفاتورة ك PDF <i class="fa fa-paperclip"></i></span>--}}
{{--                </a>--}}

{{--                <a href="{{ route('admin.tax_invoice', ['purchase_order_id' => $purchase_order_returns->id, 'download' => true]) }}"--}}
{{--                   class="btn btn-primary btn-sm btn-block">--}}
{{--                    <span> تحميل الفاتورة <i class="fa fa-download"></i></span>--}}
{{--                </a>--}}

                <!-- purchaseOrders -->
                <a href="{{ route('admin.purchaseOrderReturns.index') }}" class="btn btn-warning btn-sm btn-block">
                    <span>الذهاب الى صفحة فواتير مردودات المشتريات <i class="fa fa-backward"></i></span>
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
                                                <td align="center" colspan="2">فاتورة مردودات مشتريات </td>
                                            </tr>
                                            <tr>
                                                <td align="center" colspan="2">Sales return Invoice</td>
                                            </tr>
                                        </table>
                                        <br><br>
                                        @php
                                            $seller_name                = 'مركز الكراج الملكي لصيانة السيارات';
                                            $vat_number                 = '300014551300003';
                                            $invoice_date               = $purchase_order_returns -> created_at -> format('d/m/Y - h:i:s');
                                            $total_invoice_amount       = $purchase_order_returns -> total_return_items;
                                            $total_vat                  = $purchase_order_returns -> total_vat;
                                            $generatedString = \App\Traits\HelperTrait::SETQRCODE($seller_name, $vat_number, $invoice_date, $total_invoice_amount,$total_vat);
                                        @endphp
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <div id="QRCode">
                                                        <img src="{{ $generatedString }}" width="150">
                                                    </div>
                                                </td>
                                                <td align="center" width="20%">
                                                    <div>رقم الفاتورة</div>
                                                    <br>
                                                    <div>وقت اصدار الفاتورة</div>
                                                    <br>
                                                    <div>تاريخ اصدار الفاتورة</div>
                                                </td>
                                                <td align="center" width="20%">
                                                    <div>{{ $purchase_order_returns -> invoice_number }}</div>
                                                    <br>
                                                    <div>{{ $purchase_order_returns -> created_at -> format('h:i:s a') }}</div>
                                                    <br>
                                                    <div> {{ $purchase_order_returns -> created_at -> format('d/m/Y') }}</div>
                                                </td>
                                                <td align="center" width="20%">
                                                    <div>Invoice Number</div>
                                                    <br>
                                                    <div>Invoice Issue Time</div>
                                                    <br>
                                                    <div>Invoice Issue Date</div>
                                                </td>
                                                @if ($purchase_order_returns -> branch -> name != 'مؤسسة فادي جمال بغدادي للتجارة')
                                                    <td align="left" width="20%">
                                                        <div class="logo">
                                                            <img src="{{ asset('storage/logo150x150.png') }}" alt="logo">
                                                        </div> <!-- end logo -->
                                                    </td>
                                                @endif
                                            </tr>
                                        </table>
                                        <table width="50%" align="center" class="items">
                                            <tr>
                                                <th style="text-align: center" colspan="2">بيانات فاتورة الشراء الاصلية</th>
                                            </tr>
                                            <tr>
                                                <th style="text-align: center">رقم الفاتورة</th>
                                                <td style="text-align: center">{{ $purchase_order_returns -> purchaseOrder -> invoice_number }}</td>
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
                                                <th class="text-center">
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
                                            @foreach ($purchase_order_returns -> purchaseOrderReturnProducts as $product)
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
                                                <td align="center">{{ $purchase_order_returns -> total }} دولار</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">مجموع الخصومات</td>
                                                <td colspan="4" class="text-center">Discount</td>
                                                <td align="center">{{ $purchase_order_returns -> discount }} دولار</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">الاجمالى الخاضع للضريبة (غير شامل ضريبة القيمة
                                                    المضافة)
                                                </td>
                                                <td colspan="4" class="text-center"> ( Total Taxable Amount ( Excluding
                                                    VAT
                                                </td>
                                                <td align="center">{{ $purchase_order_returns -> taxable_amount }} دولار</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">مجموع ضريبة القيمة المضافة</td>
                                                <td colspan="4" class="text-center">Total VAT</td>
                                                <td align="center">{{ $purchase_order_returns -> total_vat }} دولار</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">اجمالى مبلغ الاصناف المردودة</td>
                                                <td colspan="4" class="text-center">Total Amount Due</td>
                                                <td align="center">{{ $purchase_order_returns -> total_return_items }} دولار</td>
                                            </tr>
                                        </table>
                                        @if ($purchase_order_returns -> notes)
                                            <div class="notes" style="padding-top: 20px">
                                                <table class="notes_table" width="100%">
                                                    <tr>
                                                        <th style="text-align: center">ملاحظات</th>
                                                        <td align="center">{{ $purchase_order_returns -> notes }}</td>
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
            direction: rtl;
            font-size: 10px;
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

