@php
    $pageType = __('trans.edit');
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
                        <div class="error_messages text-center">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('delete'))
                                <div class="alert alert-danger">
                                    {{ session('delete') }}
                                </div>
                            @endif
                        </div>
                        <div class="card-header">
                            <h3 class="text-center"><i class="fa fa-cart-arrow-down"></i> {{ $pageType .' '. $pageItem }}</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['route' => ['admin.purchaseOrders.update', $purchaseOrder -> id], 'method' => 'put', 'id' => 'purchaseOrder']) !!}
                            {!! Form::hidden('supplier_id', $purchaseOrder -> supplier_id) !!}
                            {!! Form::hidden('branch_id', $purchaseOrder -> branch_id) !!}
                            {!! Form::hidden('invoice_date', $purchaseOrder -> invoice_date) !!}
                            <div class="col-xs-12 col-custom-style">
                                <div class="row">
                                    <div class="col-xs-12 col-md-3">
                                        {!! Form::label('invoice_number', __('trans.invoice number'), ['class' => 'control-label']) !!}
                                        {!! Form::text('invoice_number', $purchaseOrder -> invoice_number , ['class' => 'form-control invoice_number']) !!}
                                    </div>
                                    {{--                                    <div class="col-xs-12 col-md-4">--}}
                                    {{--                                        {!! Form::label('invoice_date', __('trans.invoice date'), ['class' => 'control-label']) !!}--}}
                                    {{--                                        {!! Form::text('invoice_date', null , ['class' => 'form-control', 'data-date-format' => 'yyyy-mm-dd']) !!}--}}
                                    {{--                                    </div>--}}
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
                                            <th>{{ __('trans.sub category') }}</th>
                                            <th>{{ __('trans.quantity') }}</th>
                                            <th>{{ __('trans.purchasing price') }}</th>
                                            <th>{{ __('trans.amount taxable') }}</th>
                                            <th>{{ __('trans.discount') }}</th>
                                            <th style="display:none">{{ __('trans.tax amount') }}</th>
                                            <th>الإجمالي بعد الخصم</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($purchaseOrder -> purchaseOrderProducts -> sortByDesc('id') as $key => $product)
                                            <tr id="{{ $product -> item_code }}">
                                                <input  type="hidden" class="product_quantity" value="">
                                                <td><div class="btn btn-danger btn-sm removeField"><i class="fa fa-trash"></i></div></td>
                                                <td><input id="item_code_" name="product_data[{{ $key }}][item_code]" type="text" class="form-control item_code" value="{{ $product -> item_code }}" readonly></td>
                                                <td><input id="item_name_" name="product_data[{{ $key }}][item_name]" type="text" class="form-control item_name" value="{{ $product -> item_name }}" readonly title="{{ $product -> item_name }}"></td>
                                                <td>
                                                    <select class="form-control category category_{{ $key }}" name="product_data[{{ $key }}][sub_category_id]">
                                                        <option value="">-- اختر القسم --</option>
                                                        @foreach($categories_list as $category)
                                                            <optgroup label="{{ $category -> name }}"></optgroup>
                                                            @foreach($category -> subCategories as $subCategory)
                                                                <option {{ $product -> sub_category_id == $subCategory -> id ? 'selected' : '' }} value="{{ $subCategory -> id }}">{{ $subCategory -> name }}</option>
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input id="item_quantity_" name="product_data[{{ $key }}][item_quantity]" type="text" class="form-control item_quantity onlyUsedForValidation" value="{{ $product -> item_quantity }}" autocomplete="off"><span class="item_quantity_error" style="color: red; display: none"></span></td>
                                                <td><input id="item_price_" name="product_data[{{ $key }}][item_price]" type="text" class="form-control item_price momo onlyUsedForValidation" value="{{ $product -> item_price }}" autocomplete="off"><span class="item_price_error" style="color: red; display: none"></span></td>
                                                <td><input id="item_amount_taxable_" name="product_data[{{ $key }}][item_amount_taxable]" type="text" class="form-control item_amount_taxable" value="{{ $product -> item_amount_taxable }}" readonly></td>
                                                <td>
                                                    <div class="input-group my-group">
                                                        <select id="discount_type_" class="form-control discount_type" value="{{ $product -> item_discount_type }}" name="product_data[{{ $key }}][item_discount_type]">
                                                            <option value="0" {{ $product -> item_discount_type == 0 ? 'selected' : '' }}>دولار</option>
                                                            <option value="1" {{ $product -> item_discount_type == 1 ? 'selected' : '' }}>%</option>
                                                        </select>
                                                        <input id="item_discount_" name="product_data[{{ $key }}][item_discount]" type="text" class="form-control item_discount" value="{{ $product -> item_discount }}" autocomplete="off">
                                                    </div>
                                                </td>
                                                <input  type="hidden" name="product_data[{{ $key }}][item_discount_amount]" class="item_discount_amount" value="{{ $product -> item_discount_amount }}">
                                                <input  type="hidden" name="product_data[{{ $key }}][item_sub_total_after_discount]" class="item_sub_total_after_discount" value="{{ $product -> item_sub_total_after_discount }}">
                                                <td style="display:none"><input id="item_tax_amount_" name="product_data[{{ $key }}][item_tax_amount]" type="text" class="form-control item_tax_amount" value="{{ $product -> item_tax_amount }}" readonly></td>
                                                <td><input id="item_sub_total_" name="product_data[{{ $key }}][item_sub_total]" type="text" class="form-control item_sub_total" value="{{ $product -> item_sub_total }}" readonly></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- end col 8 -->
                            <div class="col-xs-12 col-custom-style">
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label">الاجمالى قبل الخصم</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total" class="form-control total" id="total" value="{{ $purchaseOrder -> total }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount" class="col-sm-4 col-form-label">مجموع الخصومات</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="discount" class="form-control discount" id="discount" value="{{ $purchaseOrder -> discount }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="taxable_amount" class="col-sm-4 col-form-label">الاجمالى بعد الخصم</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="taxable_amount" class="form-control taxable_amount" id="taxable_amount" value="{{ $purchaseOrder -> taxable_amount }}">
                                    </div>
                                </div>
                                <div class="form-group row" style="display:none">
                                    <label for="total_vat" class="col-sm-4 col-form-label">مجموع ضريبة القيمة المضافة</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total_vat" class="form-control total_vat" id="total_vat" value="{{ $purchaseOrder -> total_vat }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="total_amount_due" class="col-sm-4 col-form-label">اجمالى المبلغ المستحق</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total_amount_due" class="form-control total_amount_due" id="total_amount_due" value="{{ $purchaseOrder -> total_amount_due }}">
                                        <input type="hidden" class="form-control total_amount_due_hidden_input_without_round" id="total_amount_due_hidden_input_without_round" value="{{ $purchaseOrder -> total_amount_due }}">
                                    </div>
                                    <span id="rounding_amount" class="btn btn-success btn-sm">تقريب المبلغ</span>
                                </div>
                                <hr>
                                <div class="col-xs-12 col-custom-style">
                                        <div class="form-group">
                                            <input class="supplier_discount_button" type="checkbox" name="supplier_discount" id="supplier_discount_button" {{ $purchaseOrder -> supplier_discount ? 'checked': '' }}>
                                            <label for="supplier_discount_button">خصم المورد</label>
                                        </div>
                                        <div class="supplier_discount_container">
                                            @if ($purchaseOrder -> supplier_discount)
                                                <label for="supplier_discount_calculator">مبلغ الخصم</label>
                                                <div class="input-group my-group supplier_discount_amount" id="supplier_discount_calculator">
                                                    <select id="supplier_discount_type" class="form-control supplier_discount_type" name="supplier_discount_type">
                                                        <option value="0" {{ $purchaseOrder -> supplier_discount_type == 0 ? 'selected': '' }}>دولار</option>
                                                        <option value="1" {{ $purchaseOrder -> supplier_discount_type == 1 ? 'selected': '' }}>%</option>
                                                    </select>
                                                    <input id="supplier_discount" name="supplier_discount" type="text" class="form-control" value="{{ $purchaseOrder -> supplier_discount }}" autocomplete="off">
                                                    <input id="supplier_discount_amount" name="supplier_discount_amount" type="hidden">
                                                </div>
                                            @endif
                                        </div>

                                </div><!-- end col 12 -->
                                <div>
                                    <label for=""><b>طريقة الدفع</b></label>
                                    <div class="form-group">
                                        <input class="payment_method" type="checkbox" name="payment_method" id="cash" value="كاش" {{ $purchaseOrder -> payment_method ? 'checked': '' }}>
                                        <label for="cash">كاش</label>
                                    </div>

                                    <div class="form-group">
                                        <input class="payment_method" type="checkbox" name="payment_method_bank" id="bank_transfer" value="تحويل بنكى" {{ $purchaseOrder -> payment_method_bank == 'تحويل بنكى' ? 'checked': '' }}>
                                        <label for="bank_transfer">تحويل بنكى</label>

                                        <input class="payment_method" type="checkbox" name="payment_method_bank" id="network" value="شبكة" {{ $purchaseOrder -> payment_method_bank == 'شبكة' ? 'checked': '' }}>
                                        <label for="network">شبكة</label>

                                        <input class="payment_method" type="checkbox" name="payment_method_bank" id="stc_pay" value="STC-Pay" {{ $purchaseOrder -> payment_method_bank == 'STC-Pay' ? 'checked': '' }}>
                                        <label for="stc_pay">STC-Pay</label>
                                    </div>
                                    <span id="payment_method_error" style="color: red; display: none"></span>
                                </div>

                                <div id="amounts_group_field">
                                    @if ($purchaseOrder -> amount_paid)
                                        <div id="safe_amount_field" class="form-group row">
                                            <label for="amount_paid" class="col-sm-4 col-form-label">المبلغ المدفوع فى الخزنة</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="amount_paid" class="form-control amount_paid" id="amount_paid" value="{{ $purchaseOrder -> amount_paid }}" autocomplete="off">
                                            </div>
                                        </div>
                                    @endif
                                    @if ($purchaseOrder -> amount_paid_bank)
                                        <div id="bank_field" class="form-group row">
                                            <label for="amount_paid_bank" class="col-sm-4 col-form-label">المبلغ المدفوع فى البنك</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="amount_paid_bank" class="form-control amount_paid_bank" id="amount_paid_bank" value="{{ $purchaseOrder -> amount_paid_bank }}" autocomplete="off">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <label for="amount_due" class="col-sm-4 col-form-label">المبلغ المتبقى</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="amount_due" class="form-control amount_due" id="amount_due" value="{{ $purchaseOrder -> amount_due }}">
                                    </div>
                                    <span id="amount_due_error" style="color: red; display: none"></span>
                                </div>

                            </div> <!-- end col 12 -->

                            <div class="col-xs-12 col-custom-style">
                                <div class="form-group">
                                    <lable><b>ملاحظات</b></lable>
                                    <textarea name="notes" id="note" rows="5" class="form-control" placeholder="ملاحظات">{{ $purchaseOrder -> notes }}</textarea>
                                </div>
                            </div> <!-- end col 12 -->
                            <div class="col-xs-12 col-custom-style">
                                <div  style="width: 50%; margin: 0 auto">
                                    <div class="form-inline" style="text-align: center">
                                        {!! Form::submit('تعديل', ['class' => 'form-control btn btn-primary', 'name' => 'soft_save']) !!}
                                        {!! Form::submit(' اصدار الفاتورة النهائية و اغلاق الفاتورة', ['class' => 'form-control btn btn-danger', 'name' => 'final_save']) !!}
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
    <!-- Prevent Double Submission Form -->
    <script src="{{ asset('js/preventDoubleSubmissionForm.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- Calculator Script -->
    <script src="{{ asset('js/includes/purchase_order/edit/calculatorScript_edit_page.js') }}"></script>
    <script src="{{ asset('js/includes/purchase_order/edit/supplier_discount.js') }}"></script>
    <script src="{{ asset('js/jquryValidation/jquery.form.js') }}"></script>
    <script src="{{ asset('js/jquryValidation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/jquryValidation/additional-methods.min.js') }}"></script>

    @if (LaravelLocalization::getCurrentLocale() === 'ar')
        <!-- RTL Files -->
        <script src="{{ asset('js/jquryValidation/messages_ar.min.js') }}"></script>
    @endif

    <script>
        $(document).ready(function () {
            $('input[name="payment_method"]').on('change', function () {
                if ($(this).is(':checked'))
                {
                    if (!$('#safe_amount_field').length)
                    {
                        let content =
                            '<div id="safe_amount_field" class="form-group row">\n' +
                            '<label for="amount_paid" class="col-sm-4 col-form-label">المبلغ المدفوع فى\n' +
                            'الخزنة</label>\n' +
                            '<div class="col-sm-2">\n' +
                            '<input type="text" name="amount_paid" class="form-control amount_paid"\n' +
                            'id="amount_paid" autocomplete="off">\n' +
                            '</div>\n' +
                            '<span id="amount_paid_error" style="display: none; color: red"></span>\n' +
                            '</div>';
                        $('#amounts_group_field').prepend(content);
                    }

                }else{
                    if ($('#safe_amount_field').length)
                    {
                        $('#safe_amount_field').remove();
                    }
                }
            })

            // on change payment method create new field to pay into bank
            $('input[name="payment_method_bank"]').on('change', function () {
                $(this).siblings('input[type="checkbox"]').not(this).prop('checked', false);
                if ($('input[name="payment_method_bank"]:checked').length > 0)
                {
                    if (!$('#bank_field').length)
                    {
                        let content =
                            '<div id="bank_field" class="form-group row">\n' +
                            '<label for="amount_paid_bank" class="col-sm-4 col-form-label">المبلغ المدفوع فى البنك</label>\n' +
                            '<div class="col-sm-2">\n' +
                            '<input type="text" name="amount_paid_bank"  class="form-control amount_paid_bank" id="amount_paid_bank" autocomplete="off">\n' +
                            '</div>\n'+
                            '<span id="amount_paid_bank_error" style="display: none; color: red"></span>\n' +
                            '</div>';
                        $('#amounts_group_field').append(content);
                    }

                }else{
                    if ($('#bank_field').length)
                    {
                        $('#bank_field').remove();
                    }
                }
            })

            // if page contain any class hasError stop submitted form
            $('input[type="submit"]').on('click', function (e) {
                if ($('.hasError').length > 0)
                {
                    e.preventDefault();
                }
            })
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
            // JQuery Validation
            $('form').on('submit', function (e) {
                $('select.branch_id').each(function () {$(this).rules('add', {required: true});});
                $('input.invoice_number').each(function () {$(this).rules('add', {required: true, digits:true});});
                $('input.invoice_date').each(function () {$(this).rules('add', {required: true});});
                $('select.category').each(function () {$(this).rules('add', {required: true});});
                $('input.item_quantity').each(function () {$(this).rules('add', {required: true, digits:true, range:[1,100000]});});
                $('input.item_price').each(function () {$(this).rules('add', {required: true, range:[0,1000000]});});
                $('input#item_discount').each(function () {$(this).rules('add', {digits:true, range:[1,100000]});});
                $('input#item_amount_paid').each(function () {$(this).rules('add', {required: true, digits:true});});

                e.preventDefault();
            });
            // JQuery Validation
            $("#purchaseOrder").validate({
                submitHandler: function(form) {
                    form.submit();
                }
            });
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
            count = $('#purchase_order_products tbody').find('tr').length;
            categories = JSON.parse('{!! $categories !!}');

            $(document).on('click', '.addProduct',function (e) {
                e.preventDefault();
                productRow = $(this).closest('tr');
                productName = productRow.find('.product_name').text();
                productCode = productRow.find('.product_code').text().replaceAll(' ', '_');
                productPrice = productRow.find('.product_price').text();
                productQuantity = productRow.find('.product_quantity').text();
                html = '<tr class="'+productCode+'">\n' +
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
