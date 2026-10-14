@php
    $pageType = __('trans.create');
    $pageItem = __('trans.purchase order')

@endphp
@extends('admin.layouts.app')

@section('title', $pageType.' '.$pageItem)

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div>
                <div class="card card-success">
                    <div class="error_messages">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="card-header">
                            <h3 class="text-center"><i class="fa fa-cart-arrow-down"></i> {{ $pageType .' '. $pageItem }}
                                <a href="{{ route('admin.supplierTransactions', $target_supplier -> id) }}">{{ '[ '.$target_supplier -> name .' ]'}}</a>
                            </h3>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['route' => 'admin.purchaseOrders.store', 'method' => 'post', 'id' => 'purchaseOrders']) !!}
                            {!! Form::hidden('supplier_id', request('supplier_id'), ['id' => 'supplier_id']) !!}
                            <div class="col-xs-12 col-custom-style">
                                <div class="row">
                                    <div class="col-xs-12 col-md-4">
                                        @php($branch_id = auth()->user()->branch -> id?? '')
                                        {!! Form::label('branch_id', __('trans.branch'), ['class' => 'control-label']) !!}
                                        @if (array_key_exists($branch_id, $branches))
                                            {!! Form::select('branch_id', [auth()->user()->branch -> id => auth()->user()->branch -> name] ,null , ['class' => 'form-control branch_id']) !!}
                                        @else
                                            {!! Form::select('branch_id', [''=>'-- اختر الفرع --'] + $branches ,null , ['class' => 'form-control branch_id']) !!}
                                        @endif
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        {!! Form::label('invoice_number', __('trans.invoice number'), ['class' => 'control-label']) !!}
                                        {!! Form::text('invoice_number', null , ['class' => 'form-control invoice_number']) !!}
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        {!! Form::label('invoice_date', __('trans.invoice date'), ['class' => 'control-label']) !!}
                                        {!! Form::text('invoice_date', null , ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd']) !!}
                                    </div>
                                </div>
                            </div> <!-- end col 12 -->
                            <div style="margin: 0; padding: 0" class="col-xs-12 col-md-3 col-custom-style">
                                <div style="margin: 0; padding-left: 0; padding-right: 0;" class="col-xs-12">
                                    <div class="search_area" style="padding: 10px 0;"> <!-- search box -->
                                        <input type="search" name="" id="search_in_product_code" class="form-control" placeholder="-- بحث عن طريق الاسم او الكود --">
                                    </div>
                                </div> <!-- end search area -->
                                <div style="margin: 0; padding-left: 0; padding-right: 0;" class="col-xs-12">
                                    <div class="product_codes" style="border: #0a0a0a 1px solid; height: 350px; overflow-y: scroll; padding: 5px;">
                                        <table class="table table-responsive">
                                            <thead style="position: sticky; top: 0; background: #42a65a">
                                            <tr>
                                                <th>{{ __('trans.product code') }}</th>
                                                <th>{{ __('trans.product name') }}</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody id="product_code" class="product_code">

                                            </tbody>

                                        </table>
                                    </div>
                                </div> <!-- end codes area -->
                            </div> <!-- end col 4 -->
                            <div style="margin: 0; padding: 0" class="col-xs-12 col-md-9 col-custom-style">
                                <div class="purchase_order_products" style="border: #0a0a0a 1px solid; height: 400px; overflow-y: scroll; padding: 5px; margin-top: 10px">
                                    <table class="table" id="purchase_order_products">
                                        <thead style="position: sticky; top: 0; background: #42a65a; z-index: 999">
                                        <tr>
                                            <th></th>
                                            <th>{{ __('trans.product code') }}</th>
                                            <th>{{ __('trans.product name') }}</th>
                                            <th>{{ __('trans.category') }}</th>
                                            <th>{{ __('trans.quantity') }}</th>
                                            <th>{{ __('trans.purchasing price') }}</th>
{{--                                            <th>{{ __('trans.selling price') }}</th>--}}

                                            <th>{{ __('trans.amount taxable') }}</th>
                                            <th>{{ __('trans.discount') }}</th>
                                            

                                            <th style="display:none">{{ __('trans.tax amount') }}</th>
                                            <th>الإجمالي بعد الخصم</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- end col 8 -->
                            <div class="col-xs-12 col-custom-style">
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label">الاجمالى قبل الخصم</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total" class="form-control total" id="total">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount" class="col-sm-4 col-form-label">مجموع الخصومات</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="discount" class="form-control discount" id="discount">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="taxable_amount" class="col-sm-4 col-form-label">الاجمالى بعد الخصم</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="taxable_amount" class="form-control taxable_amount" id="taxable_amount">
                                    </div>
                                </div>
                                <div class="form-group row" style="display:none">
                                    <label for="total_vat" class="col-sm-4 col-form-label">مجموع ضريبة القيمة المضافة</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total_vat" class="form-control total_vat" id="total_vat">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="total_amount_due" class="col-sm-4 col-form-label">اجمالى المبلغ المستحق</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total_amount_due" class="form-control total_amount_due" id="total_amount_due">
                                    </div>
                                    <span id="rounding_amount" class="btn btn-success btn-sm">تقريب المبلغ</span>
                                </div>
                                <div class="col-xs-12 col-custom-style">
                                    <div class="form-group">
                                        <input class="supplier_discount_button" type="checkbox" id="supplier_discount_button">
                                        <label for="supplier_discount_button">خصم المورد</label>
                                    </div>
                                    <div class="supplier_discount_container"></div>
                                    <span id="supplier_discount_error" style="color: red; display: none"></span>
                                </div>
                                <div>
                                    <label for=""><b>طريقة الدفع</b></label>
                                    <div class="form-group">
                                        <input class="payment_method" type="checkbox" name="payment_method" id="cash" value="كاش">
                                        <label for="cash">كاش</label>
                                    </div>

                                    <div class="form-group">
                                        <input class="payment_method" type="checkbox" name="payment_method_bank" id="bank_transfer" value="تحويل بنكى">
                                        <label for="bank_transfer">تحويل بنكى</label>

                                        <input class="payment_method" type="checkbox" name="payment_method_bank" id="network" value="شبكة">
                                        <label for="network">شبكة</label>

                                        <input class="payment_method" type="checkbox" name="payment_method_bank" id="stc_pay" value="STC-Pay">
                                        <label for="stc_pay">STC-Pay</label>
                                    </div>
                                    <span id="payment_method_error" style="color: red; display: none"></span>
                                </div>
                                <div id="amounts_group_field"></div>
                                <div class="form-group row">
                                    <label for="amount_due" class="col-sm-4 col-form-label">المبلغ المتبقى</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="amount_due" class="form-control amount_due" id="amount_due">
                                    </div>
                                </div>
                            </div> <!-- end col 12 -->
                            <div class="col-xs-12 col-custom-style">
                                <div class="form-group">
                                    <lable><b>ملاحظات</b></lable>
                                    <textarea name="notes" id="note" rows="5" class="form-control" placeholder="ملاحظات"></textarea>
                                </div>
                            </div> <!-- end col 12 -->
                            <div class="col-xs-12 col-custom-style">
                                <div  style="width: 50%; margin: 0 auto">
                                    <div class="form-inline" style="text-align: center">
                                        {!! Form::submit('حفظ مؤقت', ['class' => 'form-control btn btn-warning', 'name' => 'soft_save']) !!}
                                        {!! Form::submit('اصدار الفاتورة النهائية', ['class' => 'form-control btn btn-success', 'name' => 'final_save']) !!}
                                    </div>
                                </div>
                            </div> <!-- end col 12 -->
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('links')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/dist/css/createPurchase&SalePages.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/select2/dist/css/select2.min.css') }}">

    <style>
        .info th, .info td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .custom_width
        {
            width: 100%;
        }
        .my-group .form-control{
            width:50%;
        }
    </style>
@endpush
@push('scripts')

    <!-- Select2 -->
    <script src="{{ asset('assets/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- Calculator Script -->
    <script src="{{ asset('js/purchase_order_calculatorScript.js') }}"></script>

    <script src="{{ asset('js/jquryValidation/jquery.form.js') }}"></script>
    <script src="{{ asset('js/jquryValidation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/jquryValidation/additional-methods.min.js') }}"></script>

    <!-- includes files -->
    <!-- supplier discount -->
    <script src="{{ asset('js/includes/purchase_order/create/supplier_discount.js') }}"></script>
    <!-- jquery_validation -->
    <script src="{{ asset('js/includes/purchase_order/create/jquery_validation.js') }}"></script>
    <!-- create payment method -->
    <script src="{{ asset('js/includes/purchase_order/create/create_payment_method.js') }}"></script>
    <!-- create payment method bank -->
    <script src="{{ asset('js/includes/purchase_order/create/create_payment_method_bank.js') }}"></script>
    <!-- Compare the total amount due and the supplier discount -->
    <script src="{{ asset('js/includes/purchase_order/create/compare_total_amount_due_and_supplier_discount.js') }}"></script>
    <!-- Prevent Double Submission Form -->
    <script src="{{ asset('js/preventDoubleSubmissionForm.js') }}"></script>

    @if (LaravelLocalization::getCurrentLocale() === 'ar')
        <!-- RTL Files -->
        <script src="{{ asset('js/jquryValidation/messages_ar.min.js') }}"></script>
    @endif

    <script>
        $(document).ready(function () {

            $(document).on('keyup change', '#amount_paid, #amount_paid_bank, #branch_id, input[name="payment_method"], input[name="payment_method_bank"]', function () {
                let branch_id = $('#branch_id').val();
                if (branch_id)
                {
                    getPaymentMethod();
                }else
                {
                    $('#amount_paid_error').removeClass('hasError').css('display','none').text();
                    $('#amount_paid_bank_error').removeClass('hasError').css('display','none').text();
                }
            })

            // check if amount paid larger than money in safe money
            function getPaymentMethod() {
                let branch_id = $('#branch_id').val();
                let amount_paid = 0 ;
                let amount_paid_bank = 0 ;
                if (branch_id)
                {
                    if ($('input[name="payment_method"]').is(':checked'))
                    {
                        amount_paid = $('input[name="amount_paid"]').val() || 0;
                    }
                    if ($('input[name="payment_method_bank"]:checked').length > 0)
                    {
                        amount_paid_bank = $('#amount_paid_bank').val() || 0;
                    }
                    // ajax call to send search keyword to get all records like this keyword exception exceptCodes and append result to product_code area
                    $.ajax({
                        url: "{{ route('admin.purchaseOrder.check_amount_from_moneySafe_and_bank') }}",
                        method: 'GET',
                        data: {branch_id: branch_id, amount_paid: amount_paid, amount_paid_bank: amount_paid_bank},
                        success: function (data) {

                            if(amount_paid > data.safe_money_amount)
                            {
                                let messageError = 'المبلغ الموجود فى الخزينة غير كافى';
                                $('#amount_paid_error').addClass('hasError').css('display','inline').text(messageError);
                            }
                            else
                            {
                                $('#amount_paid_error').removeClass('hasError').css('display','none').text();

                            }
                            if(amount_paid_bank > data.bank_amount)
                            {
                                let messageError = 'المبلغ الموجود فى البنك غير كافى';
                                $('#amount_paid_bank_error').addClass('hasError').css('display','inline').text(messageError);
                            }
                            else
                            {
                                $('#amount_paid_bank_error').removeClass('hasError').css('display','none').text();

                            }
                        }
                    })
                }
            }

        })
    </script>

    <script>

        $(document).ready(function () {

            //Initialize Select2 Elements
            $('.select2').select2()

            // ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // set datepicker
            $("#invoice_date").datepicker().datepicker("setDate", new Date());

            getAllProductsInBranch();

            // get all product codes from elements id to excepted records code equal ids coming from database
            function getAllProductsInBranch (keyword = '')
            {
                let html,
                    search,
                    count,
                    productRow,
                    productCode,
                    productName,
                    productPrice,
                    productQuantity,
                    content;


                // ajax call to send search keyword to get all records like this keyword exception exceptCodes and append result to product_code area
                $.ajax({
                    url: "{{ route('admin.search_product_code') }}",
                    method: 'GET',
                    data: {keyword: keyword},
                    success: function (data) {

                        $('#product_code').empty();
                        html = '';

                        $.each(data.result, function (index, element) {
                            let elementCode = element.code.replaceAll(' ', '_');
                            html +=     '<tr>\n' +
                                '    <td class="product_code">'+ elementCode +'</td>\n' +
                                '    <td class="product_name">'+ element.name +'</td>\n' +
                                '    <td><a href="" id="product_code_'+elementCode+'"  class="btn btn-success btn-sm addProduct"><i class="fa fa-plus"></i></a></td>\n' +
                                '</tr>';
                        })
                        $('#product_code').append(html);

                        $('.purchase_order_products tbody tr').each(function () {
                            let code = $(this).find('.item_code').val();
                            $('#product_code_'+ code).addClass('btn-default disabled').removeClass('btn-success');
                        });
                    }
                })
            }
            // on typing in search input call getAllProductsInBranch function
            $('#search_in_product_code').on('keyup', function () {
                search = $(this).val();
                getAllProductsInBranch(search);
            })
            count = 1;
            categories = JSON.parse('{!! $categories !!}');

            $(document).on('click', '.addProduct',function (e) {
                e.preventDefault();
                productRow = $(this).closest('tr');
                productName = productRow.find('.product_name').text();
                productCode = productRow.find('.product_code').text().replaceAll(' ', '_');
                productPrice = productRow.find('.product_price').text();
                productQuantity = productRow.find('.product_quantity').text();
                html = '<tr id="'+productCode+'">\n' +
                    '<td><div class="btn btn-danger btn-sm removeField"><i class="fa fa-trash"></i></div></td>\n' +
                    '<td><input id="item_code_' + count + '" name="product_data[' + count + '][item_code]" type="text" class="form-control item_code" value="'+productCode+'" readonly></td>\n' +
                    '<td><input id="item_name_' + count + '" name="product_data[' + count + '][item_name]" type="text" class="form-control item_name" value="'+productName+'" readonly title="'+productName+'"></td>\n' +
                    '                        <td>\n' +
                    '                            <select class="form-control category category_' + count + '" name="product_data[' + count + '][sub_category_id]">\n' +
                    '                            <option value="">-- اختر القسم --</option>\n' +
                    '                            </select>\n' +
                    '                        </td>\n' +
                    '<td><input id="item_quantity_' + count + '" name="product_data[' + count + '][item_quantity]" type="text" class="form-control item_quantity onlyUsedForValidation" autocomplete="off"></td>\n' +
                    '<td><input id="item_price_' + count + '" name="product_data[' + count + '][item_price]" type="text" class="form-control item_price onlyUsedForValidation" autocomplete="off"></td>\n' +
                    '<td><input id="item_amount_taxable_' + count + '" name="product_data[' + count + '][item_amount_taxable]" type="text" class="form-control item_amount_taxable" readonly></td>\n' +
                    '<td>' +
                    '    <div class="input-group my-group">\n' +
                    '        <select id="discount_type_' + count + '" class="form-control discount_type" name="product_data[' + count + '][item_discount_type]">\n' +
                    '            <option value="0">دولار</option>\n' +
                    '            <option value="1">%</option>\n' +
                    '        </select>\n' +
                    '       <input id="item_discount_' + count + '" name="product_data[' + count + '][item_discount]" type="text" class="form-control item_discount" autocomplete="off">\n' +
                    '    </div>\n'+
                    '</td>\n' +
                    '<input  type="hidden" name="product_data[' + count + '][item_discount_amount]" class="item_discount_amount">\n' +
                    '<input  type="hidden" name="product_data[' + count + '][item_sub_total_after_discount]" class="item_sub_total_after_discount">\n' +
                    '<td style="display:none"><input id="item_tax_amount_' + count + '" name="product_data[' + count + '][item_tax_amount]" type="text" class="form-control item_tax_amount" readonly></td>\n' +
                    '<td><input id="item_sub_total_' + count + '" name="product_data[' + count + '][item_sub_total]" type="text" class="form-control item_sub_total" readonly></td>\n' +
                    '</tr>';
                $('#purchase_order_products tbody').prepend(html);
                $(this).removeClass('btn-success').addClass('btn-default disabled');

                content = '';

                // get and each categories and sub catgories and insert into select box
                $.each(categories, function (index, element) {

                    content += '<optgroup label="' + element.name + '">';

                    $.each(element.sub_categories, function (index, element) {
                        content += '<option value="'+ element.id +'">' + element.name + '</option>';
                    });

                    content += '</optgroup>';
                });
                $(".category_"+count ).append(content);

                count++
            });
        })
    </script>

@endpush
