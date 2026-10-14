@php
    $pageType = __('trans.edit');
    $pageItem = __('trans.price list')

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
                            <h3 class="text-center"><i class="fa fa-cart-arrow-down"></i> {{ $pageType .' '. $pageItem }}</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['route' => ['admin.priceList.update', $priceList -> id], 'method' => 'put', 'id' => 'priceList']) !!}
                            <div class="col-xs-12 col-custom-style">
                                <div class="row">
                                    <div class="col-xs-12 col-md-3">
                                        @php($branch_id = auth()->user()->branch -> id?? '')
                                        {!! Form::label('branch_id', __('trans.branch'), ['class' => 'control-label']) !!}
                                        @if (array_key_exists($branch_id, $branches))
                                            {!! Form::select('branch_id', [auth()->user()->branch -> id => auth()->user()->branch -> name] ,null , ['class' => 'form-control branch_id']) !!}
                                        @else
                                            {!! Form::select('branch_id', [''=>'-- اختر الفرع --'] + $branches ,$priceList -> branch_id , ['class' => 'form-control branch_id']) !!}
                                        @endif
                                    </div>
                                    <div class="col-xs-12 col-md-3">
                                        {!! Form::label('chassis_number', __('trans.chassis number'), ['class' => 'control-label']) !!}
                                        {!! Form::text('chassis_number', $priceList -> chassis_number, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-xs-12 col-md-3">
                                        {!! Form::label('price_list_number', __('trans.price list number'), ['class' => 'control-label']) !!}
                                        {!! Form::text('price_list_number', $priceList -> price_list_number ?? '', ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-xs-12 col-md-3 pull-right">
                                        <button class="btn btn-success pull-right" id="addNewRow"><i class="fa fa-plus"></i> اضافة صنف جديد </button>
                                    </div>
                                </div>
                            </div> <!-- end col 12 -->

                            <div style="margin: 0; padding: 0" class="col-xs-12 col-md-12 col-custom-style">
                                <div class="sale_order_products" style="border: #0a0a0a 1px solid; height: 400px; overflow-y: scroll; padding: 5px; margin-top: 10px">
                                    <table class="table" id="sale_order_products">
                                        <thead style="position: sticky; top: 0; background: #42a65a">
                                        <tr>
                                            <th></th>
                                            {{--                                            <th>{{ __('trans.product code') }}</th>--}}
                                            <th>{{ __('trans.product name') }}</th>
                                            <th>{{ __('trans.quantity') }}</th>
                                            <th>{{ __('trans.selling price') }}</th>
                                            <th>{{ __('trans.amount taxable') }}</th>
                                            <th>{{ __('trans.discount') }}</th>
                                            <th style="display:none">{{ __('trans.tax amount') }}</th>
                                            <th>الإجمالي بعد الخصم</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($priceList -> priceListProducts -> sortByDesc('id') as $key => $product)
                                            <tr>
                                                <td><div class="btn btn-danger btn-sm removeField"><i class="fa fa-trash"></i></div></td>
                                                {{--                                                <td><input id="item_code_' + count + '" name="product_data[{{ $key }}][item_code]" type="text" class="form-control item_code" value="'+productCode+'" readonly></td>--}}
                                                <td><input id="item_name_{{ $key }}" name="product_data[{{ $key }}][item_name]" type="text" class="form-control item_name" value="{{ $product -> item_name}}"></td>
                                                <td><input id="item_quantity_{{ $key }}" name="product_data[{{ $key }}][item_quantity]" type="text" class="form-control item_quantity onlyUsedForValidation" value="{{ $product -> item_quantity}}" autocomplete="off"></td>
                                                <td><input id="item_price_{{ $key }}" name="product_data[{{ $key }}][item_price]" type="text" class="form-control item_price onlyUsedForValidation" value="{{ $product -> item_price}}" autocomplete="off"></td>
                                                <td><input id="item_amount_taxable_{{ $key }}" name="product_data[{{ $key }}][item_amount_taxable]" type="text" class="form-control item_amount_taxable" value="{{ $product -> item_amount_taxable}}" readonly></td>
                                                <td>
                                                    <div class="input-group my-group">
                                                        <select id="discount_type_{{ $key }}" class="form-control discount_type" name="product_data[{{ $key }}][item_discount_type]">
                                                            <option value="0" {{ $product -> item_discount_type == 0 ? 'selected' : '' }}>دولار</option>
                                                            <option value="1" {{ $product -> item_discount_type == 1 ? 'selected' : '' }}>%</option>
                                                        </select>
                                                        <input id="item_discount_{{ $key }}" name="product_data[{{ $key }}][item_discount]" type="text" class="form-control item_discount" value="{{ $product -> item_discount}}" autocomplete="off">
                                                    </div>
                                                </td>
                                                <input type="hidden" name="product_data[{{ $key }}][item_discount_amount]" class="item_discount_amount" value="{{ $product -> item_discount_amount}}">
                                                <input type="hidden" name="product_data[{{ $key }}][item_sub_total_after_discount]" class="item_sub_total_after_discount" value="{{ $product -> item_sub_total_after_discount}}">
                                                <td style="display:none"><input id="item_tax_amount_{{ $key }}" name="product_data[{{ $key }}][item_tax_amount]" type="text" class="form-control item_tax_amount" value="{{ $product -> item_tax_amount}}" readonly></td>
                                                <td><input id="item_sub_total_{{ $key }}" name="product_data[{{ $key }}][item_sub_total]" type="text" class="form-control item_sub_total" value="{{ $product -> item_sub_total}}" readonly></td>
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
                                        <input readonly type="text" name="total" class="form-control total" id="total" value="{{ $priceList -> total }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount" class="col-sm-4 col-form-label">مجموع الخصومات</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="discount" class="form-control discount" id="discount" value="{{ $priceList -> discount }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="taxable_amount" class="col-sm-4 col-form-label">الاجمالى بعد الخصم</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="taxable_amount" class="form-control taxable_amount" id="taxable_amount" value="{{ $priceList -> taxable_amount }}">
                                    </div>
                                </div>
                                <div class="form-group row" style="display:none">
                                    <label for="total_vat" class="col-sm-4 col-form-label">مجموع ضريبة القيمة المضافة</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total_vat" class="form-control total_vat" id="total_vat" value="{{ $priceList -> total_vat }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="total_amount_due" class="col-sm-4 col-form-label">اجمالى المبلغ المستحق</label>
                                    <div class="col-sm-2">
                                        <input readonly type="text" name="total_amount_due" class="form-control total_amount_due" id="total_amount_due" value="{{ $priceList -> total_amount_due }}">
                                    </div>
                                    <span id="rounding_amount" class="btn btn-success btn-sm">تقريب المبلغ</span>
                                </div>
                            </div> <!-- end col 12 -->
                            <div class="col-xs-12 col-custom-style">
                                <div class="form-group">
                                    <lable><b>ملاحظات</b></lable>
                                    <textarea name="notes" id="note" rows="5" class="form-control" placeholder="ملاحظات">{{ $priceList -> notes }}</textarea>
                                </div>
                            </div> <!-- end col 12 -->
                            <div class="col-xs-12 col-custom-style">
                                <div  style="width: 50%; margin: 0 auto">
                                    <div class="form-inline" style="text-align: center">
                                        {!! Form::submit($pageType, ['class' => 'form-control btn btn-success']) !!}
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


    <style>
        .info th, .info td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .my-group .form-control{
            width:50%;
        }
    </style>
@endpush
@push('scripts')

    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

    <!-- Calculator Script -->
    <script src="{{ asset('js/calculatorScript_edit_page.js') }}"></script>
{{--    <script src="{{ asset('js/calculatorScript.js') }}"></script>--}}

    <script src="{{ asset('js/jquryValidation/jquery.form.js') }}"></script>
    <script src="{{ asset('js/jquryValidation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/jquryValidation/additional-methods.min.js') }}"></script>

    @if (LaravelLocalization::getCurrentLocale() === 'ar')
        <!-- RTL Files -->
        <script src="{{ asset('js/jquryValidation/messages_ar.min.js') }}"></script>
    @endif
    <script>

        // if page contain any class hasError stop submitted form
        $('input[type="submit"]').on('click', function (e) {
            if ($('.hasError').length > 0)
            {
                e.preventDefault();
            }
        })
        // check if client balance less than amount due of invoice
        $(document).on('keyup', ':input', function () {
            let client_id = $('#client_id').val();
            // console.log(client_id)
            $.ajax({
                url: "{{ route('admin.getClientBalance') }}",
                method: 'GET',
                data: {client_id: client_id},
                success: function (client_balance) {
                    let amount_due = $('#amount_due').val();
                    // console.log(amount_due)
                    // console.log(client_balance)
                    if(amount_due != 0 && client_balance < amount_due)
                    {
                        $('#amount_due_error').addClass('hasError').css({'display': 'inline', 'font-size': 'x-small', 'font-style': 'italic', 'margin-bottom': '5px', 'font-weight': '700'}).text('رصيد حساب العميل لا يغطى قيمة المبلغ المتبقى');
                    }
                    else
                    {
                        $('#amount_due_error').removeClass('hasError').css('display','none').text();
                    }
                }
            })
        })
    </script>

    <script>

        $(document).ready(function () {

            // JQuery Validation
            $('form').on('submit', function (e) {
                $('select.branch_id').each(function () {$(this).rules('add', {required: true});});
                $('input#chassis_number').each(function () {$(this).rules('add', {required: true});});
                $('input.item_name').each(function () {$(this).rules('add', {required: true});});
                $('input.item_quantity').each(function () {$(this).rules('add', {required: true, digits:true, range:[1,100000]});});
                $('input.item_price').each(function () {$(this).rules('add', {required: true, range:[0,1000000]});});
                $('input.item_discount').each(function () {$(this).rules('add', {number:true, range:[1,1000000]});});
                $('input#amount_paid').each(function () {$(this).rules('add', {number:true});});
                e.preventDefault();
            });
            // JQuery Validation
            $("#priceList").validate({
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

            $('#branch_id, #search_by_category, #search_in_product_code').on('change keyup', function () {
                getAllProductsInBranch();
            })

            // get all product codes from elements id to excepted records code equal ids coming from database
            function getAllProductsInBranch ()
            {
                let html,
                    search,
                    count,
                    productRow,
                    productCode,
                    productName,
                    productPrice,
                    branch_id = $('#branch_id').val(),
                    category = $('#search_by_category').val(),
                    keyword = $('#search_in_product_code').val(),
                    productQuantity,
                    content;
                // console.log(branch_id);

                // ajax call to send search keyword to get all records like this keyword exception exceptCodes and append result to product_code area
                $.ajax({
                    url: "{{ route('admin.get_all_products_in_branch') }}",
                    method: 'GET',
                    data: {keyword: keyword, category: category, branch_id: branch_id},
                    success: function (data) {

                        $('#product_code').empty();
                        html = '';
                        $.each(data.result, function (index, element) {
                            let button = '';
                            let elementCode = element.code.replaceAll(' ', '_');
                            if (element.quantity > 0)
                            {
                                button = '<a href="" id="product_code_'+elementCode+'"  class="btn btn-success btn-sm addProduct"><i class="fa fa-plus"></i></a>';
                            }
                            html +=     '<tr id="product_row_id_'+elementCode+'">\n' +
                                '    <td class="product_code">'+ elementCode +'</td>\n' +
                                '    <td title="'+ element.sub_category.name +'" class="product_name">'+ element.name +'</td>\n' +
                                '    <td class="product_quantity">'+ element.quantity +'</td>\n' +
                                '    <td class="product_price" style="display: none">'+ element.price +'</td>\n' +
                                '    <td>'+button+'</td>\n' +
                                '    <td class="original_quantity" style="display: none">'+ element.quantity +'</td>\n' +
                                '</tr>';
                        })
                        $('#product_code').append(html);

                        // $('.sale_order_products tbody tr').each(function () {
                        //     let code = $(this).find('.item_code').val();
                        //     $('#product_code_'+ code).addClass('btn-default disabled').removeClass('btn-success');
                        // });
                    }
                })
            }
            count = $('#sale_order_products tbody').find('tr').length;

            // on click to add product button init product row and pass into side area
            $(document).on('click', '#addNewRow',function (e) {
                e.preventDefault();
                html = '<tr>\n' +
                    '<td><div class="btn btn-danger btn-sm removeField"><i class="fa fa-trash"></i></div></td>\n' +
                    '<td><input id="item_name_' + count + '" name="product_data[' + count + '][item_name]" type="text" class="form-control item_name"></td>\n' +
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
                $('#sale_order_products tbody').prepend(html);
                // $(this).removeClass('btn-success').addClass('btn-default disabled');
                count++
            });
        }) // end ready function
    </script>

@endpush
